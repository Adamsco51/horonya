<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BilsOflading;
use App\Models\TypeTravail;
use App\Models\Client;
use App\Models\User;

class BilsOfladingSeeder extends Seeder
{
    /**
     * Exécute le seeder pour la table BilsOflading.
     * Crée des données de test pour les connaissements (Bills of Lading).
     */
    public function run(): void
    {
        // Récupération des IDs existants pour les relations
        $typeTravailIds = TypeTravail::pluck('id')->toArray();
        $clientIds = Client::pluck('id')->toArray();
        $userIds = User::pluck('id')->toArray();

        // Vérification que les tables liées ont des données
        if (empty($typeTravailIds) || empty($clientIds) || empty($userIds)) {
            $this->command->warn('Attention: Assurez-vous que les tables type_travails, clients et users contiennent des données avant d\'exécuter ce seeder.');
            return;
        }

        // Données de test pour les BL
        $bilsOfladingData = [
            [
                'bl_number' => 'BL2024001',
                'nbr_conteneur' => 2,
                'type_of_conteneur' => '20ft',
                'category' => 'Import',
                'note' => 'Marchandises fragiles - Manipulation avec précaution',
                'ship_name' => 'MSC MEDITERRANEAN',
                'type_travail_id' => $typeTravailIds[array_rand($typeTravailIds)],
                'client_id' => $clientIds[array_rand($clientIds)],
                'created_by' => $userIds[array_rand($userIds)],
            ],
            [
                'bl_number' => 'BL2024002',
                'nbr_conteneur' => 1,
                'type_of_conteneur' => '40ft',
                'category' => 'Export',
                'note' => 'Produits alimentaires - Respecter la chaîne du froid',
                'ship_name' => 'MAERSK ESSEX',
                'type_travail_id' => $typeTravailIds[array_rand($typeTravailIds)],
                'client_id' => $clientIds[array_rand($clientIds)],
                'created_by' => $userIds[array_rand($userIds)],
            ],
            [
                'bl_number' => 'BL2024003',
                'nbr_conteneur' => 3,
                'type_of_conteneur' => '20ft',
                'category' => 'Import',
                'note' => 'Équipements électroniques - Éviter l\'humidité',
                'ship_name' => 'CMA CGM MARCO POLO',
                'type_travail_id' => $typeTravailIds[array_rand($typeTravailIds)],
                'client_id' => $clientIds[array_rand($clientIds)],
                'created_by' => $userIds[array_rand($userIds)],
            ],
            [
                'bl_number' => 'BL2024004',
                'nbr_conteneur' => 1,
                'type_of_conteneur' => '40ft HC',
                'category' => 'Export',
                'note' => 'Matières premières industrielles',
                'ship_name' => 'HAPAG LLOYD BERLIN',
                'type_travail_id' => $typeTravailIds[array_rand($typeTravailIds)],
                'client_id' => $clientIds[array_rand($clientIds)],
                'created_by' => $userIds[array_rand($userIds)],
            ],
            [
                'bl_number' => 'BL2024005',
                'nbr_conteneur' => 2,
                'type_of_conteneur' => '20ft',
                'category' => 'Import',
                'note' => 'Textiles et vêtements - Inspection qualité requise',
                'ship_name' => 'EVERGREEN EVER GIVEN',
                'type_travail_id' => $typeTravailIds[array_rand($typeTravailIds)],
                'client_id' => $clientIds[array_rand($clientIds)],
                'created_by' => $userIds[array_rand($userIds)],
            ],
            [
                'bl_number' => 'BL2024006',
                'nbr_conteneur' => 4,
                'type_of_conteneur' => '40ft',
                'category' => 'Export',
                'note' => 'Produits chimiques - Manipulation spécialisée requise',
                'ship_name' => 'COSCO SHIPPING UNIVERSE',
                'type_travail_id' => $typeTravailIds[array_rand($typeTravailIds)],
                'client_id' => $clientIds[array_rand($clientIds)],
                'created_by' => $userIds[array_rand($userIds)],
            ],
            [
                'bl_number' => 'BL2024007',
                'nbr_conteneur' => 1,
                'type_of_conteneur' => '20ft',
                'category' => 'Import',
                'note' => 'Pièces automobiles - Vérification inventaire nécessaire',
                'ship_name' => 'YANG MING SUCCESS',
                'type_travail_id' => $typeTravailIds[array_rand($typeTravailIds)],
                'client_id' => $clientIds[array_rand($clientIds)],
                'created_by' => $userIds[array_rand($userIds)],
            ],
            [
                'bl_number' => 'BL2024008',
                'nbr_conteneur' => 2,
                'type_of_conteneur' => '40ft HC',
                'category' => 'Export',
                'note' => 'Machines industrielles - Arrimage spécial',
                'ship_name' => 'ONE COMMITMENT',
                'type_travail_id' => $typeTravailIds[array_rand($typeTravailIds)],
                'client_id' => $clientIds[array_rand($clientIds)],
                'created_by' => $userIds[array_rand($userIds)],
            ],
        ];

        // Création des enregistrements BL
        foreach ($bilsOfladingData as $data) {
            BilsOflading::create($data);
        }

        $this->command->info('✅ ' . count($bilsOfladingData) . ' Bills of Lading créés avec succès!');
    }
}
