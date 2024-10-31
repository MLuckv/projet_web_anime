import pandas as pd
import sys
import json
from surprise import Dataset, Reader, SVD
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import LabelEncoder

# Chargement des données depuis les arguments
ratings_json = sys.argv[1]  # Données de notation
anime_json = sys.argv[2]     # Données d'anime
user_id = int(sys.argv[3])   # ID de l'utilisateur
n = int(sys.argv[4])          # Nombre de recommandations

# Convertir les chaînes JSON en DataFrame
ratings_df = pd.DataFrame(json.loads(ratings_json))
anime_df = pd.DataFrame(json.loads(anime_json))

# Assurez-vous que les colonnes anime_id sont au bon type
ratings_df['anime_id'] = ratings_df['anime_id'].astype('Int64')
anime_df['anime_id'] = anime_df['anime_id'].astype('Int64')

# Effectuer la fusion
df = pd.merge(ratings_df, anime_df[['anime_id', 'genres']], on='anime_id', how='left')

# Supprimer les lignes avec des valeurs manquantes
df.dropna(inplace=True)

# Encoder les utilisateurs et les animes
user_encoder = LabelEncoder()
anime_encoder = LabelEncoder()

df['user_id'] = user_encoder.fit_transform(df['user_id'])
df['anime_id'] = anime_encoder.fit_transform(df['anime_id'])

# Créer l'ensemble d'entraînement
train_df, test_df = train_test_split(df, test_size=0.2)
reader = Reader(rating_scale=(0.5, 5))
data = Dataset.load_from_df(train_df[['user_id', 'anime_id', 'rating']], reader)
trainset = data.build_full_trainset()
model_svd = SVD()
model_svd.fit(trainset)

def get_top_n_recommendations(user_id, n=5):
    user_anime = df[df['user_id'] == user_id]['anime_id'].unique()
    all_anime = df['anime_id'].unique()
    anime_to_predict = list(set(all_anime) - set(user_anime))

    user_anime_pairs = [(user_id, anime_id, 0) for anime_id in anime_to_predict]
    predictions_cf = model_svd.test(user_anime_pairs)

    top_n_recommendations = sorted(predictions_cf, key=lambda x: x.est, reverse=True)[:n]

    top_n_anime_ids = [int(pred.iid) for pred in top_n_recommendations]

    return top_n_anime_ids  # Retourner uniquement les IDs des animes recommandés

# Obtenir les recommandations
recommendations = get_top_n_recommendations(user_encoder.transform([user_id])[0], n)

# Créer la sortie finale au format JSON
output = {
    'recommendations': recommendations  # Inclure seulement les IDs ici
}

# Afficher les recommandations
print(json.dumps(output))
