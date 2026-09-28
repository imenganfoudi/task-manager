<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $memberRole = Role::firstOrCreate(['name' => 'member', 'guard_name' => 'web']);

        $admin = User::updateOrCreate(
            ['email' => 'admin@demo.com'],
            [
                'name' => 'Demo Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->syncRoles([$adminRole]);

        $member = User::updateOrCreate(
            ['email' => 'member@demo.com'],
            [
                'name' => 'Demo Member',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $member->syncRoles([$memberRole]);

        $projects = [
            [
                'name' => 'Site E-commerce',
                'description' => 'Refonte complète de la boutique en ligne.',
                'tasks' => [
                    ['Créer la page d\'accueil', 'done', 'admin', -5],
                    ['Intégrer le panier', 'in_progress', 'member', 3],
                    ['Configurer le paiement', 'todo', 'admin', 10],
                    ['Rédiger les pages légales', 'todo', null, 14],
                ],
            ],
            [
                'name' => 'Application Mobile',
                'description' => 'Version mobile du service de réservation.',
                'tasks' => [
                    ['Maquettes des écrans', 'done', 'member', -10],
                    ['Authentification', 'in_progress', 'admin', 2],
                    ['Notifications push', 'todo', 'member', 12],
                ],
            ],
            [
                'name' => 'Documentation API',
                'description' => 'Documenter tous les endpoints REST.',
                'tasks' => [
                    ['Lister les endpoints', 'done', 'admin', -7],
                    ['Ajouter les exemples de requêtes', 'todo', 'member', 7],
                ],
            ],
        ];

        foreach ($projects as $data) {
            $project = Project::firstOrCreate(
                ['name' => $data['name']],
                [
                    'description' => $data['description'],
                    'owner_id' => $admin->id,
                ]
            );

            foreach ($data['tasks'] as [$title, $status, $assignee, $dueInDays]) {
                Task::firstOrCreate(
                    ['project_id' => $project->id, 'title' => $title],
                    [
                        'status' => $status,
                        'assigned_to' => match ($assignee) {
                            'admin' => $admin->id,
                            'member' => $member->id,
                            default => null,
                        },
                        'due_date' => now()->addDays($dueInDays)->toDateString(),
                    ]
                );
            }
        }
    }
}