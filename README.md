# PROTO-9 : L'Éveil

Une fiction interactive à choix multiples se déroulant dans l'espace, développée dans le cadre du cours de développement web à la HEIG-VD.

## 🎯 Objectifs du Projet

- Créer une fiction interactive avec des choix multiples
- Implémenter une API RESTful avec Laravel
- Développer une interface utilisateur réactive avec Vue.js
- Gérer les erreurs et la validation des données
- Documenter l'API et les fonctionnalités

## 🛠️ Stack Technique

- **Backend**: Laravel 12.8.1, SQLite
- **Frontend**: Vue.js 3.x, Vite 6.2.6, TailwindCSS 4.x
- **Base de données**: SQLite
- **API**: RESTful versionnée (v1)

## 🚀 Installation Rapide

```bash
# Dépendances
composer install
npm install

# Configuration
cp .env.example .env
php artisan key:generate
touch database/database.sqlite

# Base de données
php artisan migrate:refresh --seed

# Lancement
php artisan serve  # Terminal 1
npm run dev       # Terminal 2
```

## 📚 API

### Base URL
```
http://127.0.0.1:8000/api/v1
```

### Endpoints Principaux
- `GET /stories` - Liste des histoires
- `GET /chapters/{id}` - Détails d'un chapitre
- `GET /choices` - Liste des choix

### Gestion des Erreurs

```json
{
    "status": "error",
    "message": "Description de l'erreur",
    "errors": null
}
```

#### Types d'Erreurs
- 404: Ressource non trouvée
- 422: Données invalides
- 401: Non authentifié
- 500: Erreur serveur

## 🎮 Jouer

Accédez à l'application via : `http://127.0.0.1:8000`

## 📝 Structure du Projet

```
app/
├── Http/
│   ├── Controllers/Api/  # Contrôleurs API
│   ├── Requests/         # Validation
│   ├── Resources/        # Formatage JSON
│   └── Responses/        # Réponses API
├── Models/               # Modèles Eloquent
└── Exceptions/          # Gestion des erreurs
```

## 🎓 Contexte

Projet développé dans le cadre du cours de développement web à la HEIG-VD, mettant en pratique :
- Architecture API RESTful
- Interface utilisateur interactive
- Gestion des erreurs
- Validation des données
- Documentation technique

## 📅 Dates de Rendu

- Backend : 12 mai 2025
- Frontend : 18 mai 2025