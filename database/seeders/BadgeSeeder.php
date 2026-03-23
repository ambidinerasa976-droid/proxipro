<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            [
                'name' => 'Nouveau Membre',
                'description' => 'Bienvenue sur la plateforme !',
                'icon' => 'fas fa-user',
                'color' => 'secondary',
                'points_required' => 0,
                'level_required' => 1
            ],
            [
                'name' => 'Actif',
                'description' => 'Plus de 10 points gagnés',
                'icon' => 'fas fa-bolt',
                'color' => 'warning',
                'points_required' => 10,
                'level_required' => 1
            ],
            [
                'name' => 'Partageur',
                'description' => '5 partages effectués',
                'icon' => 'fas fa-share-alt',
                'color' => 'info',
                'points_required' => 25,
                'level_required' => 1
            ],
            [
                'name' => 'Influenceur',
                'description' => 'Plus de 100 points',
                'icon' => 'fas fa-crown',
                'color' => 'danger',
                'points_required' => 100,
                'level_required' => 2
            ],
            [
                'name' => 'Expert',
                'description' => 'Niveau 5 atteint',
                'icon' => 'fas fa-trophy',
                'color' => 'success',
                'points_required' => 500,
                'level_required' => 5
            ],
            [
                'name' => 'Légende',
                'description' => 'Niveau 10 - Un véritable champion !',
                'icon' => 'fas fa-medal',
                'color' => 'primary',
                'points_required' => 1000,
                'level_required' => 10
            ]
        ];

        foreach ($badges as $badge) {
            Badge::create($badge);
        }
    }
}
