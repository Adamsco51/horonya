<?php

namespace App\Livewire;

use App\Models\BilsOflading;
use App\Models\Client;
use App\Models\TypeTravail;
use App\Models\User;
use App\Models\Suivie;
use Livewire\Component;
use Carbon\Carbon;

class Dashboard extends Component
{
    /**
     * Calcule les statistiques du dashboard
     */
    public function getStatistics()
    {
        return [
            'total_bls' => BilsOflading::count(),
            'total_clients' => Client::count(),
            'total_types_travail' => TypeTravail::count(),
            'total_users' => User::count(),
            'total_suivies' => Suivie::count(),
            'suivies_avec_alerte' => Suivie::avecAlerte()->count(),
            'bls_this_month' => BilsOflading::whereMonth('created_at', Carbon::now()->month)
                                           ->whereYear('created_at', Carbon::now()->year)
                                           ->count(),
            'new_clients_this_month' => Client::whereMonth('created_at', Carbon::now()->month)
                                             ->whereYear('created_at', Carbon::now()->year)
                                             ->count(),
            'recent_bls' => BilsOflading::with(['client', 'type_travail', 'creator'])
                                       ->latest()
                                       ->take(5)
                                       ->get(),
            'bls_by_type' => BilsOflading::with('type_travail')
                                        ->get()
                                        ->groupBy('type_travail.type_work')
                                        ->map(function ($group) {
                                            return $group->count();
                                        }),
            'suivies_avec_alerte_details' => Suivie::avecAlerte()
                                                  ->with(['client', 'bilsOflading', 'agentTransit'])
                                                  ->orderBy('ETA')
                                                  ->get(),
        ];
    }

    public function render()
    {
        $statistics = $this->getStatistics();
        
        return view('livewire.dashboard', compact('statistics'));
    }
}
