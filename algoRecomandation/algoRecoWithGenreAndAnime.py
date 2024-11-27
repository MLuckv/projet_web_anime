import pandas as pd
import sys
import json
from surprise import Dataset, Reader, SVD
import pymysql

# Lecture des paramètres
n = int(sys.argv[1])  # Nombre de recommandations
population_size_factor = float(sys.argv[2])  # Facteur d'échantillonnage
input_genres = json.loads(sys.argv[3])  # Genres sélectionnés
input_animes = json.loads(sys.argv[4])  # Noms des animes sélectionnés

# Configuration de la base de données
DB_CONFIG = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 'anime',
}

# Chargement des données
try:
    conn = pymysql.connect(**DB_CONFIG)

    # Récupérer les évaluations
    query_ratings = """
        SELECT r.user_id, r.anime_id, r.rating
        FROM rate r
    """
    ratings_df = pd.read_sql(query_ratings, conn)

    # Récupérer les animes
    query_anime = """
        SELECT a.id as anime_id, a.genre as genres, a.nom as anime_name
        FROM anime a
    """
    anime_df = pd.read_sql(query_anime, conn)
finally:
    conn.close()

# Traitement des données
ratings_df['anime_id'] = ratings_df['anime_id'].astype('Int64')
anime_df['anime_id'] = anime_df['anime_id'].astype('Int64')

# Filtrer les animes par genres et noms d'entrée
filtered_anime_df = anime_df[
    anime_df['genres'].apply(lambda g: any(genre in g for genre in input_genres)) |
    anime_df['anime_name'].isin(input_animes)
]

# Exclure les animes spécifiés dans `input_animes` des recommandations
anime_to_exclude = filtered_anime_df[filtered_anime_df['anime_name'].isin(input_animes)]
excluded_ids = set(anime_to_exclude['anime_id'])

# Fusion des données pour inclure uniquement les animes filtrés
df = pd.merge(ratings_df, filtered_anime_df[['anime_id', 'genres']], on='anime_id', how='inner')
df.dropna(inplace=True)

# Échantillonnage des données
def sample_ratings(population_size_factor=1.0):
    num_ratings = max(1, int(len(df) * population_size_factor))
    return df.sample(n=num_ratings, random_state=42)

sampled_df = sample_ratings(population_size_factor)

# Préparer les données pour Surprise
reader = Reader(rating_scale=(1, 10))
data = Dataset.load_from_df(sampled_df[['user_id', 'anime_id', 'rating']], reader)
trainset = data.build_full_trainset()

# Entraîner le modèle
model_svd = SVD()
model_svd.fit(trainset)

# Obtenir les recommandations
def get_top_n_recommendations(n=5):
    all_anime = set(df['anime_id'].unique())
    anime_for_recommendation = list(all_anime - excluded_ids)  # Exclure les animes spécifiés
    predictions = model_svd.test([(0, anime_id, 0) for anime_id in anime_for_recommendation])  # user_id = 0
    top_n_recommendations = sorted(predictions, key=lambda x: x.est, reverse=True)[:n]
    return [int(pred.iid) for pred in top_n_recommendations]

# Résultats
recommended_ids = get_top_n_recommendations(n)
recommended_anime = anime_df[anime_df['anime_id'].isin(recommended_ids)][['anime_id', 'anime_name']]

# Sortie JSON
output = {'recommendations': recommended_anime.to_dict(orient='records')}
print(json.dumps(output))
