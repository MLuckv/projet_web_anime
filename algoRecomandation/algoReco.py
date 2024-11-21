import pandas as pd
import pymysql
from surprise import Dataset, Reader, SVD
import json
import sys

# Variables passées en argument
user_id = int(sys.argv[1])   # ID de l'utilisateur
n = int(sys.argv[2])         # Nombre de recommandations
population_size_factor = float(sys.argv[3])

# Configuration de la base de données
DB_CONFIG = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 'anime',
}

# Connexion à la base de données
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
        SELECT a.id as anime_id, a.genre as genres
        FROM anime a
    """
    anime_df = pd.read_sql(query_anime, conn)
finally:
    conn.close()

# Assurez-vous que les colonnes anime_id sont au bon type
ratings_df['anime_id'] = ratings_df['anime_id'].astype('Int64')
anime_df['anime_id'] = anime_df['anime_id'].astype('Int64')

# Fusionner les DataFrames
df = pd.merge(ratings_df, anime_df[['anime_id', 'genres']], on='anime_id', how='left')
df.dropna(inplace=True)  # Supprimer les lignes avec des valeurs manquantes

# Échantillonner les données
def sample_ratings(user_id, population_size_factor=1.0):
    user_ratings = df[df['user_id'] == user_id]
    other_ratings = df[df['user_id'] != user_id]
    num_other_ratings = max(1, int(len(other_ratings) * population_size_factor))
    sampled_other_ratings = other_ratings.sample(n=num_other_ratings, random_state=42)
    return pd.concat([user_ratings, sampled_other_ratings])

sampled_df = sample_ratings(user_id, population_size_factor)

# Préparer les données pour Surprise
reader = Reader(rating_scale=(1, 10))
data = Dataset.load_from_df(sampled_df[['user_id', 'anime_id', 'rating']], reader)
trainset = data.build_full_trainset()

# Entraîner le modèle SVD
model_svd = SVD()
model_svd.fit(trainset)

# Recommander des animes
def get_top_n_recommendations(user_id, n=5):
    user_anime = df[df['user_id'] == user_id]['anime_id'].unique()
    all_anime = df['anime_id'].unique()
    anime_to_predict = list(set(all_anime) - set(user_anime))
    predictions_cf = model_svd.test([(user_id, anime_id, 0) for anime_id in anime_to_predict])
    top_n_recommendations = sorted(predictions_cf, key=lambda x: x.est, reverse=True)[:n]
    return [int(pred.iid) for pred in top_n_recommendations]

recommendations = get_top_n_recommendations(user_id, n)

# Sortie des recommandations
output = {'recommendations': recommendations}
print(json.dumps(output))
