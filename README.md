# NekoStream – Plateforme fictive de streaming d'anime

## Présentation
Ce projet académique simule la création d’une entreprise fictive de streaming d’anime.  
L’objectif est de combiner **développement web**, **ingénierie data** et **recommandation intelligente** pour proposer une expérience personnalisée aux utilisateurs.

Le projet s’appuie sur une base de données enrichie à grande échelle (plus d’1 million de lignes importées via **PDI / Pentaho Data Integration**) afin d’analyser les tendances et alimenter des algorithmes de recommandation.

## Objectifs du projet
- Concevoir une application web complète autour d’un catalogue d’anime.
- Étudier les tendances de consommation via des visualisations statistiques.
- Proposer des recommandations pertinentes pour :
  - un **nouvel utilisateur** (cold start),
  - un **utilisateur existant** (personnalisation via historique).

## Fonctionnalités principales
- **Recherche d’anime** et consultation des détails.
- **Statistiques interactives** sur les genres et la popularité.
- **Recommandation “nouvel utilisateur”** :
  - sélection de genres et d’animes appréciés,
  - suggestions générées à partir de ces préférences.
- **Recommandation “utilisateur existant”** :
  - connexion/profil utilisateur,
  - recommandations personnalisées via l’algorithme **SVD**.

## Stack technique

### Backend & Web
- **PHP 8.2+**
- **Symfony 7**
- **Doctrine ORM / DBAL**
- **Twig** (templating)

### Frontend
- **Bootstrap 5**
- **JavaScript**
- **Chart.js** (visualisation de données)

### Data & Recommandation
- **Python 3**
- **Pandas**
- **PyMySQL**
- **scikit-surprise (SVD)** pour la recommandation collaborative
- **PDI (Pentaho Data Integration)** pour l’import et la préparation massive des données

### Base de données
- **MySQL**

## Architecture (vue d’ensemble)
- `src/Controller` : logique applicative (home, stats, profil, recommandations)
- `src/Repository` : accès aux données et agrégations
- `src/Entity` : modèle métier (anime, user, rate)
- `templates/` : vues Twig
- `algoRecomandation/` : scripts Python de recommandation

## Installation rapide

### Prérequis
- PHP 8.2+
- Composer
- MySQL
- Python 3 + pip

### 1) Cloner et installer les dépendances PHP
```bash
composer install
```

### 2) Configurer l’environnement
Mettre à jour le fichier `.env` pour la connexion MySQL (`DATABASE_URL`).

### 3) Préparer la base de données
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### 4) Installer les dépendances Python (recommandation)
```bash
pip install pandas pymysql scikit-surprise
```

### 5) Lancer le serveur Symfony
```bash
symfony server:start
```
ou
```bash
php -S 127.0.0.1:8000 -t public
```

## Parcours de recommandation

### Nouvel utilisateur (cold start)
1. Choisir ses genres favoris.
2. Sélectionner des animes qu’il apprécie.
3. Recevoir une liste d’animes recommandés.

### Utilisateur existant
1. Se connecter via l’espace profil.
2. L’algorithme **SVD** exploite l’historique de notes/interactions.
3. Affichage de recommandations personnalisées.

## Validation du projet
- Tests unitaires disponibles via PHPUnit (`php bin/phpunit`).

## Limites actuelles
- Certains chemins Python sont encore codés en dur dans les contrôleurs et doivent être externalisés en variables d’environnement.
- Les performances et la qualité des recommandations peuvent encore être améliorées (feature engineering, tuning SVD, métriques offline/online).

## Perspectives
- Industrialisation du pipeline data (ETL automatisé, monitoring).
- API de recommandation dédiée.
- Amélioration UX (onboarding, feedback explicite sur les recos).
- Ajout d’évaluations de modèles plus avancées (precision@k, recall@k, A/B testing).

---
Projet réalisé dans le cadre d’un travail de création d’entreprise fictive orientée **streaming d’anime** et **data intelligence**.
