<?php

namespace App\Livewire\Suivie;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Suivie;
use App\Models\Client;
use App\Models\User;

class IndexSuivie extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $etatFilter = '';
    public $alerteOnly = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'etatFilter' => ['except' => ''],
        'alerteOnly' => ['except' => false],
    ];

    /**
     * Réinitialise la pagination lors de la recherche
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Réinitialise la pagination lors du changement de filtre
     */
    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    /**
     * Réinitialise la pagination lors du changement de filtre d'état
     */
    public function updatingEtatFilter()
    {
        $this->resetPage();
    }

    /**
     * Réinitialise la pagination lors du changement de filtre d'alerte
     */
    public function updatingAlerteOnly()
    {
        $this->resetPage();
    }

    /**
     * Supprime un suivie
     */
    public function delete($suivieId)
    {
        $suivie = Suivie::findOrFail($suivieId);
        $suivie->delete();

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Suivie supprimé avec succès!'
        ]);
    }

    /**
     * Assigne un agent de transit à un suivie
     */
    public function assignerAgent($suivieId, $agentId)
    {
        $suivie = Suivie::findOrFail($suivieId);
        $suivie->update(['agent_transit_id' => $agentId]);

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Agent assigné avec succès!'
        ]);
    }

    /**
     * Met à jour le statut d'un suivie
     */
    public function updateStatut($suivieId)
    {
        $suivie = Suivie::findOrFail($suivieId);
        $suivie->updateStatut();

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Statut mis à jour automatiquement!'
        ]);
    }

    /**
     * Rendu du composant
     */
    public function render()
    {
        $query = Suivie::with(['client', 'bilsOflading', 'agentTransit', 'creator']);

        // Filtre de recherche
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('numero_bl', 'like', '%' . $this->search . '%')
                  ->orWhereHas('client', function ($clientQuery) {
                      $clientQuery->where('nom', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Filtre par statut
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Filtre par état
        if ($this->etatFilter) {
            $query->where('etat', $this->etatFilter);
        }

        // Filtre pour les alertes uniquement
        if ($this->alerteOnly) {
            $query->avecAlerte();
        }

        $suivies = $query->orderBy('ETA', 'asc')->paginate(10);

        // Récupération des agents de transit pour l'assignation
        $agents = User::where('role', 'agent_transit')->get();

        return view('livewire.suivie.index-suivie', [
            'suivies' => $suivies,
            'agents' => $agents,
        ]);
    }
}
