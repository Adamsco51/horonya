<?php

namespace App\Livewire\Suivie;

use Livewire\Component;
use App\Models\Suivie;

class ShowSuivie extends Component
{
    public Suivie $suivie;

    /**
     * Montage du composant avec les données du suivie
     */
    public function mount(Suivie $suivie)
    {
        $this->suivie = $suivie->load(['client', 'bilsOflading', 'agentTransit', 'creator']);
    }

    /**
     * Calcule les informations d'alerte
     */
    public function getAlerteInfoProperty()
    {
        $joursRestants = $this->suivie->jours_restants;
        
        if ($joursRestants < 0) {
            return [
                'type' => 'danger',
                'message' => 'Navire en retard de ' . abs($joursRestants) . ' jour(s)',
                'icon' => 'exclamation-triangle'
            ];
        } elseif ($joursRestants == 0) {
            return [
                'type' => 'warning',
                'message' => 'Navire arrive aujourd\'hui!',
                'icon' => 'clock'
            ];
        } elseif ($joursRestants <= 7) {
            return [
                'type' => 'info',
                'message' => 'Alerte: ' . $joursRestants . ' jour(s) restant(s)',
                'icon' => 'info-circle'
            ];
        }
        
        return [
            'type' => 'success',
            'message' => $joursRestants . ' jour(s) restant(s)',
            'icon' => 'check-circle'
        ];
    }

    /**
     * Obtient la couleur du badge de statut
     */
    public function getStatusBadgeColorProperty()
    {
        return match($this->suivie->status) {
            'traitement' => 'warning',
            'stockage' => 'info',
            default => 'secondary'
        };
    }

    /**
     * Obtient la couleur du badge d'état
     */
    public function getEtatBadgeColorProperty()
    {
        return match($this->suivie->etat) {
            'arrive' => 'success',
            'retard' => 'danger',
            'en_cours' => 'primary',
            default => 'secondary'
        };
    }

    /**
     * Supprime le suivie
     */
    public function delete()
    {
        try {
            $this->suivie->delete();

            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'Suivie supprimé avec succès!'
            ]);

            return redirect()->route('suivies.index');

        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Met à jour le statut automatiquement
     */
    public function updateStatutAutomatique()
    {
        try {
            $this->suivie->updateStatut();
            $this->suivie->refresh();

            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'Statut mis à jour automatiquement!'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Rendu du composant
     */
    public function render()
    {
        return view('livewire.suivie.show-suivie');
    }
}
