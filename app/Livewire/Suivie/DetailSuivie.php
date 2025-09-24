<?php

namespace App\Livewire\Suivie;

use Livewire\Component;
use App\Models\Suivie;
use App\Models\EtapeTraitement;
use App\Models\SuiviTraitement;
use Illuminate\Support\Facades\Storage;

/**
 * Composant Livewire pour afficher les détails complets d'un suivi
 * Inclut les informations du BL, client, agent et l'historique des traitements
 */
class DetailSuivie extends Component
{
    public $suivie;
    public $suivieId;
    public $etapes;
    public $suiviTraitements;
    public $showTraitementModal = false;

    /**
     * Initialise le composant avec l'ID du suivi
     */
    public function mount($suivieId)
    {
        $this->suivieId = $suivieId;
        $this->loadData();
    }

    /**
     * Charge les données du suivi et ses relations
     */
    public function loadData()
    {
        $this->suivie = Suivie::with([
            'bl.client',
            'bl.typeTravail',
            'agentTransit',
            'creator',
            'suiviTraitements.etapeTraitement',
            'suiviTraitements.user'
        ])->findOrFail($this->suivieId);

        $this->etapes = EtapeTraitement::active()->ordered()->get();
        $this->suiviTraitements = $this->suivie->suiviTraitements()
            ->with(['etapeTraitement', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Ouvre le modal de traitement
     */
    public function openTraitementModal()
    {
        $this->showTraitementModal = true;
    }

    /**
     * Ferme le modal de traitement
     */
    public function closeTraitementModal()
    {
        $this->showTraitementModal = false;
        $this->loadData(); // Recharge les données après modification
    }

    /**
     * Télécharge un document joint
     */
    public function downloadDocument($suiviTraitementId, $documentPath)
    {
        $suiviTraitement = SuiviTraitement::findOrFail($suiviTraitementId);
        
        if (!Storage::exists($documentPath)) {
            session()->flash('error', 'Le document demandé n\'existe pas.');
            return;
        }

        return Storage::download($documentPath);
    }

    /**
     * Met à jour le statut du suivi
     */
    public function updateStatut($nouveauStatut)
    {
        $this->suivie->update(['statut' => $nouveauStatut]);
        $this->loadData();
        
        session()->flash('message', 'Statut mis à jour avec succès.');
    }

    /**
     * Calcule le nombre de jours restants avant l'ETA
     */
    public function getJoursRestantsProperty()
    {
        if (!$this->suivie->eta) {
            return null;
        }

        return now()->diffInDays($this->suivie->eta, false);
    }

    /**
     * Détermine la classe CSS pour l'alerte ETA
     */
    public function getAlerteEtaClassProperty()
    {
        $joursRestants = $this->joursRestants;
        
        if ($joursRestants === null) {
            return 'bg-gray-100 text-gray-800';
        }
        
        if ($joursRestants <= 0) {
            return 'bg-red-100 text-red-800';
        } elseif ($joursRestants <= 3) {
            return 'bg-orange-100 text-orange-800';
        } elseif ($joursRestants <= 7) {
            return 'bg-yellow-100 text-yellow-800';
        }
        
        return 'bg-green-100 text-green-800';
    }

    /**
     * Retourne la classe CSS pour le statut
     */
    public function getStatutClassProperty()
    {
        return match($this->suivie->statut) {
            'en_attente' => 'bg-yellow-100 text-yellow-800',
            'en_cours' => 'bg-blue-100 text-blue-800',
            'termine' => 'bg-green-100 text-green-800',
            'bloque' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Retourne le libellé du statut
     */
    public function getStatutLabelProperty()
    {
        return match($this->suivie->statut) {
            'en_attente' => 'En Attente',
            'en_cours' => 'En Cours',
            'termine' => 'Terminé',
            'bloque' => 'Bloqué',
            default => 'Inconnu'
        };
    }

    public function render()
    {
        return view('livewire.suivie.detail-suivie');
    }
}
