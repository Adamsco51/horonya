<?php

namespace App\Livewire\Suivie;

use Livewire\Component;
use App\Models\Suivie;
use App\Models\Client;
use App\Models\BilsOflading;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CreateSuivie extends Component
{
    public $client_id = '';
    public $bils_ofladings_id = '';
    public $agent_transit_id = '';
    public $ETA = '';
    public $etat = 'en_cours';
    public $status = 'stockage';
    public $availableBLs = [];

    /**
     * Règles de validation
     */
    protected function rules()
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'bils_ofladings_id' => 'nullable|exists:bils_ofladings,id',
            'agent_transit_id' => 'required|exists:users,id',
            'ETA' => 'required|date|after_or_equal:today',
            'etat' => 'required|in:en_cours,arrive,retard',
            'status' => 'required|in:stockage,traitement',
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
            'agent_transit_id.required' => 'L\'agent de transit est obligatoire.',
            'agent_transit_id.exists' => 'L\'agent de transit sélectionné n\'existe pas.',
            'ETA.required' => 'La date ETA est obligatoire.',
            'ETA.date' => 'La date ETA doit être une date valide.',
            'ETA.after_or_equal' => 'La date ETA ne peut pas être antérieure à aujourd\'hui.',
            'etat.required' => 'L\'état est obligatoire.',
            'etat.in' => 'L\'état sélectionné n\'est pas valide.',
            'status.required' => 'Le statut est obligatoire.',
            'status.in' => 'Le statut sélectionné n\'est pas valide.',
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
     * Met à jour les BL disponibles quand un client est sélectionné
     */
    public function updatedClientId()
    {
        if ($this->client_id) {
            $this->availableBLs = BilsOflading::where('client_id', $this->client_id)
                ->orderBy('bl_number')
                ->get();
            $this->bils_ofladings_id = ''; // Reset BL selection
        } else {
            $this->availableBLs = [];
            $this->bils_ofladings_id = '';
        }
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
     * Sauvegarde le nouveau suivie
     */
    public function save()
    {
        $this->validate();

        try {
            $suivie = Suivie::create([
                'client_id' => $this->client_id,
                'bils_ofladings_id' => $this->bils_ofladings_id ?: null,
                'agent_transit_id' => $this->agent_transit_id,
                'ETA' => $this->ETA,
                'etat' => $this->etat,
                'status' => $this->status,
                'created_by' => Auth::id(),
            ]);

            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'Suivie créé avec succès!'
            ]);

            return redirect()->route('suivies.index');

        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Erreur lors de la création du suivie: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Réinitialise le formulaire
     */
    public function resetForm()
    {
        $this->reset([
            'client_id',
            'bils_ofladings_id',
            'agent_transit_id',
            'ETA',
            'etat',
            'status'
        ]);
        
        $this->availableBLs = [];
        $this->resetValidation();
    }

    /**
     * Rendu du composant
     */
    public function render()
    {
        $clients = Client::orderBy('name_of_entreprise')->get();
        $agentsTransit = User::orderBy('name')->get(); // Tous les utilisateurs sont des agents de transit
        
        return view('livewire.suivie.create-suivie', [
            'clients' => $clients,
            'agentsTransit' => $agentsTransit,
            'availableBLs' => $this->availableBLs,
        ]);
    }
}
