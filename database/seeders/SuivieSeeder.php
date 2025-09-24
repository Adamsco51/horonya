<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Suivie;
use App\Models\BilsOflading;
use App\Models\Client;
use App\Models\User;
use Carbon\Carbon;

class SuivieSeeder extends Seeder
{
    /**
     * Exécute le seeder pour la table Suivie.
     * Crée des données de test pour le suivi des navires et conteneurs.
     */
    public function run(): void
    {
        // Récupération des IDs existants pour les relations
        $bilsOfladingIds = BilsOflading::pluck('id')->toArray();
        $clientIds = Client::pluck('id')->toArray();
        $userIds = User::pluck('id')->toArray();

        // Vérification que les tables liées ont des données
        if (empty($bilsOfladingIds) || empty($clientIds) || empty($userIds)) {
            $this->command->warn('Attention: Assurez-vous que les tables bils_ofladings, clients et users contiennent des données avant d\'exécuter ce seeder.');
            return;
        }

        // Données de test pour les suivies avec différents scénarios
        $suivieData = [
            [
                'bils_ofladings_id' => $bilsOfladingIds[array_rand($bilsOfladingIds)],
                'client_id' => $clientIds[array_rand($clientIds)],
                'created_by' => $userIds[array_rand($userIds)],
                'agent_transit_id' => $userIds[array_rand($userIds)],
                'ETA' => Carbon::now()->addDays(15)->format('Y-m-d'), // Dans 15 jours - Stockage
                'status' => Suivie::STATUS_STOCKAGE,
                'etat' => Suivie::ETAT_EN_COURS,
                'numero_bl' => 'BL2024001',
            ],
            [
                'bils_ofladings_id' => $bilsOfladingIds[array_rand($bilsOfladingIds)],
                'client_id' => $clientIds[array_rand($clientIds)],
                'created_by' => $userIds[array_rand($userIds)],
                'agent_transit_id' => $userIds[array_rand($userIds)],
                'ETA' => Carbon::now()->addDays(5)->format('Y-m-d'), // Dans 5 jours - Traitement (alerte)
                'status' => Suivie::STATUS_TRAITEMENT,
                'etat' => Suivie::ETAT_EN_COURS,
                'numero_bl' => 'BL2024002',
            ],
            [
                'bils_ofladings_id' => $bilsOfladingIds[array_rand($bilsOfladingIds)],
                'client_id' => $clientIds[array_rand($clientIds)],
                'created_by' => $userIds[array_rand($userIds)],
                'agent_transit_id' => $userIds[array_rand($userIds)],
                'ETA' => Carbon::now()->addDays(2)->format('Y-m-d'), // Dans 2 jours - Traitement (alerte)
                'status' => Suivie::STATUS_TRAITEMENT,
                'etat' => Suivie::ETAT_EN_COURS,
                'numero_bl' => 'BL2024003',
            ],
            [
                'bils_ofladings_id' => $bilsOfladingIds[array_rand($bilsOfladingIds)],
                'client_id' => $clientIds[array_rand($clientIds)],
                'created_by' => $userIds[array_rand($userIds)],
                'agent_transit_id' => $userIds[array_rand($userIds)],
                'ETA' => Carbon::now()->format('Y-m-d'), // Aujourd'hui - Arrivé
                'status' => Suivie::STATUS_TRAITEMENT,
                'etat' => Suivie::ETAT_ARRIVE,
                'numero_bl' => 'BL2024004',
            ],
            [
                'bils_ofladings_id' => $bilsOfladingIds[array_rand($bilsOfladingIds)],
                'client_id' => $clientIds[array_rand($clientIds)],
                'created_by' => $userIds[array_rand($userIds)],
                'agent_transit_id' => $userIds[array_rand($userIds)],
                'ETA' => Carbon::now()->subDays(3)->format('Y-m-d'), // Il y a 3 jours - En retard
                'status' => Suivie::STATUS_TRAITEMENT,
                'etat' => Suivie::ETAT_RETARD,
                'numero_bl' => 'BL2024005',
            ],
            [
                'bils_ofladings_id' => $bilsOfladingIds[array_rand($bilsOfladingIds)],
                'client_id' => $clientIds[array_rand($clientIds)],
                'created_by' => $userIds[array_rand($userIds)],
                'agent_transit_id' => $userIds[array_rand($userIds)],
                'ETA' => Carbon::now()->addDays(20)->format('Y-m-d'), // Dans 20 jours - Stockage
                'status' => Suivie::STATUS_STOCKAGE,
                'etat' => Suivie::ETAT_EN_COURS,
                'numero_bl' => 'BL2024006',
            ],
            [
                'bils_ofladings_id' => $bilsOfladingIds[array_rand($bilsOfladingIds)],
                'client_id' => $clientIds[array_rand($clientIds)],
                'created_by' => $userIds[array_rand($userIds)],
                'agent_transit_id' => $userIds[array_rand($userIds)],
                'ETA' => Carbon::now()->addDays(7)->format('Y-m-d'), // Dans 7 jours - Limite alerte
                'status' => Suivie::STATUS_TRAITEMENT,
                'etat' => Suivie::ETAT_EN_COURS,
                'numero_bl' => 'BL2024007',
            ],
            [
                'bils_ofladings_id' => $bilsOfladingIds[array_rand($bilsOfladingIds)],
                'client_id' => $clientIds[array_rand($clientIds)],
                'created_by' => $userIds[array_rand($userIds)],
                'agent_transit_id' => $userIds[array_rand($userIds)],
                'ETA' => Carbon::now()->addDays(1)->format('Y-m-d'), // Demain - Traitement urgent
                'status' => Suivie::STATUS_TRAITEMENT,
                'etat' => Suivie::ETAT_EN_COURS,
                'numero_bl' => 'BL2024008',
            ],
            [
                'bils_ofladings_id' => $bilsOfladingIds[array_rand($bilsOfladingIds)],
                'client_id' => $clientIds[array_rand($clientIds)],
                'created_by' => $userIds[array_rand($userIds)],
                'agent_transit_id' => $userIds[array_rand($userIds)],
                'ETA' => Carbon::now()->subDays(1)->format('Y-m-d'), // Hier - En retard
                'status' => Suivie::STATUS_TRAITEMENT,
                'etat' => Suivie::ETAT_RETARD,
                'numero_bl' => 'BL2024009',
            ],
            [
                'bils_ofladings_id' => $bilsOfladingIds[array_rand($bilsOfladingIds)],
                'client_id' => $clientIds[array_rand($clientIds)],
                'created_by' => $userIds[array_rand($userIds)],
                'agent_transit_id' => $userIds[array_rand($userIds)],
                'ETA' => Carbon::now()->addDays(30)->format('Y-m-d'), // Dans 30 jours - Stockage longue durée
                'status' => Suivie::STATUS_STOCKAGE,
                'etat' => Suivie::ETAT_EN_COURS,
                'numero_bl' => 'BL2024010',
            ],
        ];

        // Création des enregistrements de suivie
        foreach ($suivieData as $data) {
            Suivie::create($data);
        }

        $this->command->info('✅ ' . count($suivieData) . ' suivies créés avec succès!');
        $this->command->info('📊 Répartition des statuts:');
        $this->command->info('   - Stockage: ' . collect($suivieData)->where('status', Suivie::STATUS_STOCKAGE)->count());
        $this->command->info('   - Traitement: ' . collect($suivieData)->where('status', Suivie::STATUS_TRAITEMENT)->count());
        $this->command->info('📅 Répartition des états:');
        $this->command->info('   - En cours: ' . collect($suivieData)->where('etat', Suivie::ETAT_EN_COURS)->count());
        $this->command->info('   - Arrivé: ' . collect($suivieData)->where('etat', Suivie::ETAT_ARRIVE)->count());
        $this->command->info('   - En retard: ' . collect($suivieData)->where('etat', Suivie::ETAT_RETARD)->count());
    }
}
