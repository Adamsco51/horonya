<?php

namespace Database\Seeders;

use App\Models\TypeTravail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeTravailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer 3 types de travail spécifiques
        $types = [
            [
                'type_work' => 'Transport Maritime',
                'description' => 'Transport de marchandises par voie maritime'
            ],
            [
                'type_work' => 'Transport Terrestre',
                'description' => 'Transport de marchandises par voie terrestre'
            ],
            [
                'type_work' => 'Transport Aérien',
                'description' => 'Transport de marchandises par voie aérienne'
            ]
        ];

        foreach ($types as $type) {
            TypeTravail::create($type);
        }
    }
}
