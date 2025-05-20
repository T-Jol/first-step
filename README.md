# Fiction Interstellaire

Une collection d'histoires interactives à choix multiples se déroulant dans l'espace, où chaque décision façonne votre destin. Développé avec Laravel et Vue.js.

## 🎯 Objectifs du Projet

- Créer une expérience narrative immersive dans l'espace
- Implémenter une interface utilisateur réactive avec effets visuels
- Développer un système de choix multiples avec conséquences
- Gérer la progression et le journal de l'aventure
- Offrir une expérience utilisateur fluide et engageante

## 🛠️ Stack Technique

- **Backend**: Laravel 12.8.1
- **Frontend**: Vue.js 3.x, Vite 6.2.6
- **Base de données**: SQLite
- **API**: RESTful versionnée (v1)
- **Effets visuels**: CSS Animations, Typewriter effect

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
php artisan migrate:fresh --seed  # Cette commande recrée toutes les tables et insère les données de test

# Lancement
php artisan serve  # Terminal 1
npm run dev       # Terminal 2
```

## 📚 API

### URLs d'accès
- Application : `http://127.0.0.1:8000/`
- API : `http://127.0.0.1:8000/api/v1/`

### Endpoints Disponibles
- `GET /api/v1/stories` - Liste des histoires disponibles
- `GET /api/v1/chapters/{id}` - Détails d'un chapitre spécifique

### Exemples d'utilisation
```bash
# Accéder à l'application
http://127.0.0.1:8000/

# Accéder à un chapitre spécifique
http://127.0.0.1:8000/api/v1/chapters/1
```

### Gestion des Erreurs

```json
{
    "status": "error",
    "message": "Description de l'erreur",
    "errors": null
}
```

#### Types d'Erreurs
- 404: Chapitre non trouvé
- 422: Données invalides
- 500: Erreur serveur

## 🎮 Jouer

Accédez à l'application via : `http://127.0.0.1:8000/`

### Fonctionnalités
- Navigation fluide entre les chapitres
- Effet machine à écrire pour le texte
- Journal de progression
- Musique d'ambiance
- Interface responsive
- Effets visuels immersifs
- Reprise de l'aventure en cours
- Statistiques des histoires (nombre de chapitres, fins possibles)

## 📝 Structure du Projet

```
first-step/
├── app/
│   ├── Http/
│   │   ├── Controllers/Api/  # Contrôleurs API
│   │   │   ├── Requests/         # Validation
│   │   │   └── Resources/        # Formatage JSON
│   │   └── Models/               # Modèles Eloquent
│   └── database/
│       ├── migrations/           # Structure de la base de données
│       └── seeders/              # Données initiales
├── resources/
│   ├── js/
│   │   ├── components/       # Composants Vue.js
│   │   │   ├── ChapterView.vue    # Vue principale du chapitre
│   │   │   ├── HomeView.vue       # Page d'accueil
│   │   │   ├── TheHeader.vue      # En-tête
│   │   │   └── StarBackground.vue # Effet d'arrière-plan
│   │   └── router/           # Configuration des routes
│   └── css/                  # Styles
├── routes/
│   └── api.php              # Routes API
└── public/                  # Assets publics
```

## 🎓 Contexte

Projet développé dans le cadre du cours de développement web à la HEIG-VD, mettant en pratique :
- Développement d'une fiction interactive
- Interface utilisateur immersive
- Gestion d'état et navigation
- Effets visuels et animations
- Architecture API RESTful

## 📅 Dates de Rendu

- Backend : 12 mai 2025
- Frontend : 18 mai 2025

## 🎨 Fonctionnalités Frontend

- **Interface Immersive**
  - Effet machine à écrire pour le texte
  - Transitions fluides entre les chapitres
  - Arrière-plan animé avec étoiles
  - Musique d'ambiance avec contrôle du volume
  - Design futuriste avec effets de scanline

- **Navigation**
  - Système de choix multiples
  - Journal de progression
  - Retour à l'accueil
  - Reprise de l'aventure en cours
  - Gestion des erreurs

- **Expérience Utilisateur**
  - Design responsive
  - Animations fluides
  - Feedback visuel
  - Sauvegarde de progression
  - Statistiques des histoires

## 🔧 Développement

### Prérequis
- PHP 8.2+
- Node.js 18+
- npm 9+
- SQLite

### Commandes Utiles
```bash
# Développement
npm run dev        # Lance le serveur de développement
php artisan serve  # Lance le serveur Laravel

# Production
npm run build     # Compile les assets
php artisan optimize  # Optimise l'application
```