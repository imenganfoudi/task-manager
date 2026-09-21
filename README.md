# Task Manager — Mini CRM / Gestion de projets

Application de gestion de tâches et de projets en équipe, avec workflow Kanban et gestion des rôles.

![Tests](https://img.shields.io/badge/tests-8%20passing-brightgreen)

## Stack technique

- **Backend :** Laravel 13
- **Frontend :** Livewire 4 (composants full-stack réactifs), Alpine.js, Tailwind CSS 4
- **Autorisations :** Spatie Laravel-Permission (rôles Admin / Member)
- **Base de données :** MySQL
- **Tests :** PHPUnit (8 tests fonctionnels)

## Fonctionnalités

- 📁 **Gestion de projets** : création et listing des projets par utilisateur
- 📋 **Tableau Kanban** : tâches organisées en 3 colonnes (À faire / En cours / Terminé)
- 👤 **Assignation de tâches** à des membres de l'équipe
- 🔒 **Rôles et permissions** : seuls les administrateurs peuvent supprimer des tâches
- ⚡ **Interactivité en temps réel** via Livewire, sans écrire de JavaScript personnalisé

## Points techniques notables

- Composants Livewire 4 (Single File Components) pour la logique métier réactive
- Relations Eloquent (Project → hasMany → Task, avec owner et assignee)
- Contrôle d'accès basé sur les rôles (Spatie Permission) testé unitairement
- Tests fonctionnels couvrant la création, le déplacement Kanban et les permissions

## Installation locale

\`\`\`bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install && npm run dev
php artisan serve
\`\`\`

## Tests

\`\`\`bash
php artisan test
\`\`\`