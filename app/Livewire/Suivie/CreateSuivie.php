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
    public $numero_bl;
    public $agent_transit_id = '';
    public $ETA = '';
    public $etat = 'en_cours';
    public $status = 'stockage';
    public $availableBLs = [];
    public $joursRestants = null;

    /**
     * Règles de validation
     */
    protected function rules()
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'bils_ofladings_id' => 'nullable|exists:bils_ofladings,id',
            'numero_bl' => 'required|string|max:255|unique:suivies,numero_bl',
            'agent_transit_id' => 'nullable|exists:users,id',
            'ETA' => 'required|date',
            'etat' => 'required|in:en_cours,arrive,retard',
            'status' => 'required|in:stockage,traitement,depasse',
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
            'status.required' => 'Le statut est obligatoire.',
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
     * Met à jour automatiquement le client quand un numéro BL est saisi
     */
    public function updatedNumeroBl()
    {
        if ($this->numero_bl) {
            // Rechercher le BL correspondant au numéro saisi
            $bl = BilsOflading::where('bl_number', $this->numero_bl)->first();
            
            if ($bl) {
                // Si le BL existe, sélectionner automatiquement le client et le BL
                $this->client_id = $bl->client_id;
                $this->bils_ofladings_id = $bl->id;
                
                // Mettre à jour la liste des BL disponibles pour ce client
                $this->updatedClientId();
                
                // Message de succès
                $this->dispatch('toast', [
                    'type' => 'success',
                    'message' => 'Client automatiquement sélectionné pour le BL ' . $this->numero_bl
                ]);
            } else {
                // Si le BL n'existe pas, réinitialiser les sélections
                $this->bils_ofladings_id = '';
                // Ne pas réinitialiser client_id pour permettre la création d'un nouveau BL
            }
        } else {
            // Si le numéro BL est vide, réinitialiser les sélections
            $this->bils_ofladings_id = '';
        }
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
            
            // Si un BL spécifique n'est pas déjà sélectionné, réinitialiser la sélection
            if ($this->bils_ofladings_id && !$this->availableBLs->contains('id', $this->bils_ofladings_id)) {
                $this->bils_ofladings_id = '';
            }
        } else {
            $this->availableBLs = [];
            $this->bils_ofladings_id = '';
        }
    }

    /**
     * Met à jour automatiquement le client et les informations quand un BL existant est sélectionné
     */
    public function updatedBilsOfladingsId()
    {
        if ($this->bils_ofladings_id) {
            $bl = BilsOflading::find($this->bils_ofladings_id);
            
            if ($bl) {
                // Mettre à jour automatiquement le numéro BL et s'assurer que le client correspond
                $this->numero_bl = $bl->bl_number;
                
                // Si le client n'est pas déjà sélectionné, le sélectionner automatiquement
                if ($this->client_id != $bl->client_id) {
                    $this->client_id = $bl->client_id;
                    $this->updatedClientId(); // Recharger les BL disponibles
                }
            }
        }
    }

    /**
     * Détermine automatiquement le statut basé sur l'ETA
     * Affiche les jours restants et gère le statut 'dépassé' si ETA > 10 jours
     */
    public function updatedETA()
    {
        if ($this->ETA) {
            $eta = \Carbon\Carbon::parse($this->ETA);
            $joursRestants = (int) round(\Carbon\Carbon::today()->diffInDays($eta, false));
            
            // Déterminer le statut basé sur les jours restants
            if ($joursRestants < -10) {
                // ETA dépassée de plus de 10 jours
                $this->status = 'depasse';
            } elseif ($joursRestants <= 0) {
                // ETA atteinte ou dépassée (mais moins de 10 jours)
                $this->status = 'traitement';
            } elseif ($joursRestants <= 7) {
                // ETA dans les 7 prochains jours
                $this->status = 'traitement';
            } else {
                // ETA dans plus de 7 jours
                $this->status = 'stockage';
            }
            
            // Ajouter une propriété pour afficher les jours restants dans l'interface
            $this->joursRestants = $joursRestants;
        } else {
            $this->joursRestants = null;
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
                'numero_bl' => $this->numero_bl,
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
            'numero_bl',
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
