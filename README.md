# Task Manager — Gestion de projets et de tâches

Application de gestion de projets en équipe avec tableau Kanban et gestion des rôles.
[![Tests](https://github.com/imenganfoudi/task-manager/actions/workflows/tests.yml/badge.svg)](https://github.com/imenganfoudi/task-manager/actions/workflows/tests.yml)

## Démo en ligne

**https://task-manager-production-300f.up.railway.app**

| Rôle   | Email             | Mot de passe |
|--------|-------------------|--------------|
| Admin  | admin@demo.com    | password     |
| Member | member@demo.com   | password     |

L'admin peut supprimer des tâches, le membre non. La démo est hébergée sur un plan d'essai Railway : si le lien ne répond plus, la démo est temporairement hors ligne.

## Stack technique

- **Backend :** Laravel 13
- **Frontend :** Livewire 4 (composants full-stack réactifs), Alpine.js, Tailwind CSS 4
- **Authentification :** Laravel Breeze
- **Autorisations :** Spatie Laravel-Permission (rôles Admin / Member)
- **Base de données :** MySQL
- **Tests :** PHPUnit (31 tests)
- **Déploiement :** Docker (PHP 8.3 + Apache), Railway

## Fonctionnalités

- 📁 **Gestion de projets** : création et liste des projets
- 📋 **Tableau Kanban** : tâches en 3 colonnes (À faire / En cours / Terminé)
- 👤 **Assignation de tâches** aux membres de l'équipe
- 🔒 **Rôles et permissions** : seuls les administrateurs peuvent supprimer des tâches (vérifié côté interface et côté serveur)
- ⚡ **Interactivité en temps réel** via Livewire, sans JavaScript personnalisé

## Points techniques notables

- Composants Livewire 4 (Single File Components)
- Relations Eloquent (Project → hasMany → Task, avec owner et assignee)
- Contrôle d'accès par rôles (Spatie Permission), testé automatiquement
- Tests fonctionnels : création, déplacement Kanban, permissions, authentification
- Seeder idempotent avec comptes et données de démonstration
- Dockerfile multi-étapes (build Vite puis image PHP/Apache)

## Installation locale

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run dev
php artisan serve
```

Comptes de démonstration créés par le seeder : `admin@demo.com` et `member@demo.com` (mot de passe : `password`).

## Tests

```bash
php artisan test
```