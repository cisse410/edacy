# Projet de Gestion de Produits - Edacy Phase 2

Ce projet est une application web de gestion de produits développée dans le cadre de la Phase 2 du programme Edacy. L'application permet aux utilisateurs de gérer un catalogue de produits avec des fonctionnalités CRUD complètes.

## Technologies Utilisées

### Backend
- Laravel 12
- PHP 8
- MySQL
- Laravel Sanctum pour l'authentification
- Swagger/OpenAPI pour la documentation de l'API

### Frontend
- Vue.js 3
- Tailwind CSS
- Pinia pour la gestion d'état
- Vue Router pour la navigation
- Axios pour les requêtes HTTP

## Fonctionnalités

- Authentification utilisateur (inscription, connexion, déconnexion)
- Tableau de bord avec vue d'ensemble
- Gestion complète des produits :
  - Liste des produits avec recherche
  - Création de nouveaux produits
  - Visualisation des détails
  - Modification des produits existants
  - Suppression de produits

## Architecture du Projet

Le projet est divisé en deux parties principales :

### Backend (`/backend`)
- API RESTful développée avec Laravel
- Base de données MySQL
- Documentation Swagger intégrée
- Middleware CORS configuré
- Système d'authentification avec tokens

### Frontend (`/frontend`)
- Interface utilisateur moderne et réactive
- Composants Vue.js réutilisables
- Design responsive avec Tailwind CSS
- Gestion d'état centralisée avec Pinia
- Routes protégées avec Vue Router

## Installation

### Prérequis
- PHP >= 8.0
- Composer
- Node.js >= 16
- MySQL

### Backend

1. Naviguer vers le dossier backend :
```bash
cd backend
```

2. Installer les dépendances PHP :
```bash
composer install
```

3. Copier le fichier d'environnement :
```bash
cp .env.example .env
```

4. Configurer la base de données dans .env

5. Générer la clé d'application :
```bash
php artisan key:generate
```

6. Exécuter les migrations :
```bash
php artisan migrate
```

7. Démarrer le serveur :
```bash
php artisan serve
```

Le backend sera accessible sur http://localhost:8000

### Frontend

1. Naviguer vers le dossier frontend :
```bash
cd frontend
```

2. Installer les dépendances :
```bash
npm install
```

3. Lancer le serveur de développement :
```bash
npm run dev
```

L'application frontend sera accessible sur http://localhost:8001

## Documentation de l'API

La documentation de l'API est générée automatiquement avec Swagger/OpenAPI et est accessible à :

http://localhost:8000/api/documentation

### Points d'accès principaux

#### Authentification
- POST /api/register - Inscription d'un nouvel utilisateur
- POST /api/login - Connexion utilisateur
- POST /api/logout - Déconnexion utilisateur

#### Produits
- GET /api/products - Liste tous les produits
- POST /api/products - Crée un nouveau produit
- GET /api/products/{id} - Récupère les détails d'un produit
- PUT /api/products/{id} - Met à jour un produit
- DELETE /api/products/{id} - Supprime un produit

## Structure des Données

### Produit
```json
{
  "id": integer,
  "name": string,
  "description": string,
  "price": float,
  "quantity": integer,
  "created_at": timestamp,
  "updated_at": timestamp
}
```

## Contact

Pour toute question ou support, contactez :
- LinkedIn : [Issakha CISSE](https://www.linkedin.com/in/issakha-cisse/)
- Développeur : Issakha CISSE
