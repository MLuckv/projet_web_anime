import pandas as pd
import sys
import json
from surprise import Dataset, Reader, SVD
import random

# Chargement des données depuis les arguments
ratings_json = sys.argv[1]  # Données de notation
anime_json = sys.argv[2]     # Données d'anime
user_id = int(sys.argv[3])   # ID de l'utilisateur
n = int(sys.argv[4])         # Nombre de recommandations
population_size_factor = float(sys.argv[5])  # Facteur par rapport à la taille de la population

# Convertir les chaînes JSON en DataFrame
ratings_df = pd.DataFrame(json.loads(ratings_json))
anime_df = pd.DataFrame(json.loads(anime_json))

# Assurez-vous que les colonnes anime_id sont au bon type
ratings_df['anime_id'] = ratings_df['anime_id'].astype('Int64')
anime_df['anime_id'] = anime_df['anime_id'].astype('Int64')

# Fusionner les DataFrames
df = pd.merge(ratings_df, anime_df[['anime_id', 'genres']], on='anime_id', how='left')

# Supprimer les lignes avec des valeurs manquantes
df.dropna(inplace=True)

# Échantillonner les données en fonction de la taille de la population
def sample_ratings(user_id, population_size_factor=1.0):
    # Garder toutes les évaluations de l'utilisateur
    user_ratings = df[df['user_id'] == user_id]

    # Garder les autres évaluations
    other_ratings = df[df['user_id'] != user_id]

    # Calculer la taille d'échantillon en fonction de la population
    num_other_ratings = max(1, int(len(other_ratings) * population_size_factor))  # Assurez-vous d'avoir au moins une évaluation
    sampled_other_ratings = other_ratings.sample(n=num_other_ratings, random_state=42)

    # Combiner les évaluations de l'utilisateur et les autres évaluations échantillonnées
    sampled_df = pd.concat([user_ratings, sampled_other_ratings])

    return sampled_df

# Créer l'ensemble d'entraînement avec échantillonnage
sampled_df = sample_ratings(user_id, population_size_factor)

# Créer l'ensemble d'entraînement
reader = Reader(rating_scale=(1, 10))  # Assurez-vous que le rating_scale est correct (de 1 à 10 dans vos données)
data = Dataset.load_from_df(sampled_df[['user_id', 'anime_id', 'rating']], reader)
trainset = data.build_full_trainset()
model_svd = SVD()
model_svd.fit(trainset)

# Fonction pour obtenir les meilleures recommandations
def get_top_n_recommendations(user_id, n=5):
    user_anime = df[df['user_id'] == user_id]['anime_id'].unique()
    all_anime = df['anime_id'].unique()

    # Exclure les animes déjà notés par l'utilisateur
    anime_to_predict = list(set(all_anime) - set(user_anime))

    # Préparer les paires utilisateur-anime à prédire
    user_anime_pairs = [(user_id, anime_id, 0) for anime_id in anime_to_predict]
    predictions_cf = model_svd.test(user_anime_pairs)

    # Trier par les meilleures recommandations
    top_n_recommendations = sorted(predictions_cf, key=lambda x: x.est, reverse=True)[:n]

    top_n_anime_ids = [int(pred.iid) for pred in top_n_recommendations]

    return top_n_anime_ids  # Retourner uniquement les IDs des animes recommandés

# Obtenir les recommandations
recommendations = get_top_n_recommendations(user_id, n)

# Créer la sortie finale au format JSON
output = {
    'recommendations': recommendations
}

print(json.dumps(output))
