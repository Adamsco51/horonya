<?php

namespace App\Livewire\Suivie;

use Livewire\Component;
use App\Models\Suivie;
use App\Models\Client;
use App\Models\BilsOflading;
use App\Models\User;

class EditSuivie extends Component
{
    public Suivie $suivie;
    public $client_id;
    public $bils_ofladings_id;
    public $numero_bl;
    public $ETA;
    public $etat;
    public $status;
    public $agent_transit_id;

    /**
     * Montage du composant avec les données du suivie
     */
    public function mount(Suivie $suivie)
    {
        $this->suivie = $suivie;
        $this->client_id = $suivie->client_id;
        $this->bils_ofladings_id = $suivie->bils_ofladings_id;
        $this->numero_bl = $suivie->numero_bl;
        $this->ETA = $suivie->ETA ? $suivie->ETA->format('Y-m-d') : '';
        $this->etat = $suivie->etat;
        $this->status = $suivie->status;
        $this->agent_transit_id = $suivie->agent_transit_id;
    }

    /**
     * Règles de validation
     */
    protected function rules()
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'bils_ofladings_id' => 'nullable|exists:bils_ofladings,id',
            'numero_bl' => 'required|string|max:255|unique:suivies,numero_bl,' . $this->suivie->id,
            'ETA' => 'required|date',
            'etat' => 'required|in:en_cours,arrive,retard',
            'status' => 'required|in:stockage,traitement',
            'agent_transit_id' => 'nullable|exists:users,id',
        ];
    }

    /**
     * Messages de validation personnalisés
     */
    protected function messages()
    {
        return [
            'client_id.required' => 'Le client est obligatoire.',
            'client_id.exists' => 'Le client sélectionné n\'existe pas.',
            'numero_bl.required' => 'Le numéro BL est obligatoire.',
            'numero_bl.unique' => 'Ce numéro BL existe déjà.',
            'ETA.required' => 'La date ETA est obligatoire.',
            'ETA.date' => 'La date ETA doit être une date valide.',
            'etat.required' => 'L\'état est obligatoire.',
            'etat.in' => 'L\'état sélectionné n\'est pas valide.',
            'status.required' => 'Le statut est obligatoire.',
            'status.in' => 'Le statut sélectionné n\'est pas valide.',
            'agent_transit_id.exists' => 'L\'agent sélectionné n\'existe pas.',
        ];
    }

    /**
     * Mise à jour en temps réel de la validation
     */
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    /**
     * Détermine automatiquement le statut basé sur l'ETA
     */
    public function updatedETA()
    {
        if ($this->ETA) {
            $eta = \Carbon\Carbon::parse($this->ETA);
            $joursRestants = (int) round(\Carbon\Carbon::today()->diffInDays($eta, false));
            
            $this->status = $joursRestants <= 7 ? 'traitement' : 'stockage';
        }
    }

    /**
     * Met à jour automatiquement l'état basé sur l'ETA
     */
    public function updateEtatAutomatique()
    {
        if ($this->ETA) {
            $eta = \Carbon\Carbon::parse($this->ETA);
            $today = \Carbon\Carbon::today();
            
            if ($eta->isSameDay($today)) {
                $this->etat = 'arrive';
            } elseif ($eta->isPast()) {
                $this->etat = 'retard';
            } else {
                $this->etat = 'en_cours';
            }
        }
    }

    /**
     * Sauvegarde les modifications
     */
    public function save()
    {
        $this->validate();

        try {
            $this->suivie->update([
                'client_id' => $this->client_id,
                'bils_ofladings_id' => $this->bils_ofladings_id ?: null,
                'numero_bl' => $this->numero_bl,
                'ETA' => $this->ETA,
                'etat' => $this->etat,
                'status' => $this->status,
                'agent_transit_id' => $this->agent_transit_id ?: null,
            ]);

            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'Suivie modifié avec succès!'
            ]);

            return redirect()->route('suivies.index');

        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Erreur lors de la modification du suivie: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Rendu du composant
     */
    public function render()
    {
        $clients = Client::orderBy('nom')->get();
        $bilsOfladings = BilsOflading::orderBy('numero')->get();
        $agents = User::where('role', 'agent_transit')->orderBy('name')->get();

        return view('livewire.suivie.edit-suivie', [
            'clients' => $clients,
            'bilsOfladings' => $bilsOfladings,
            'agents' => $agents,
        ]);
    }
}
