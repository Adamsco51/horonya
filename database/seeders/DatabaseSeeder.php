<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeder les rôles et permissions en premier
        $this->call([
            RolePermissionSeeder::class,
        ]);

        // Seeder les types de travail en premier (car ils peuvent être référencés par d'autres modèles)
        $this->call([
            TypeTravailSeeder::class,
            ClientSeeder::class,
        ]);

        // Créer un utilisateur de test
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Seeder les BL et suivies après avoir créé les données de base
        $this->call([
            BilsOfladingSeeder::class,
            SuivieSeeder::class,
        ]);
    }
}
