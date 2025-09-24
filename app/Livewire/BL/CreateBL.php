<?php

namespace App\Livewire\BL;

use Livewire\Component;
use App\Models\Client;
use App\Models\BilsOflading;
use App\Models\TypeTravail;

class CreateBL extends Component
{
    public $type_travail_id;
    public $client_id;
    public $created_by;
    public $bl_number;
    public $nbr_conteneur;
    public $ship_name;
    public $type_of_conteneur;
    public $category;
    public $note;
    public $bl_clients = [];
    public $bl_types = [];

    /**
     * Règles de validation pour la création d'un BL
     */
    protected $rules = [
        'bl_number'          => 'required|string|max:12|unique:bils_ofladings,bl_number',
        'nbr_conteneur'      => 'required|integer|min:1|max:999',
        'ship_name'          => 'required|string|max:100',
        'type_of_conteneur'  => 'required|string|max:50',
        'category'           => 'nullable|string|max:50',
        'note'               => 'nullable|string|max:255',
        'client_id'          => 'required|integer|exists:clients,id',
        'type_travail_id'    => 'required|integer|exists:type_travails,id',
    ];

    /**
     * Messages de validation personnalisés
     */
    protected $messages = [
        'bl_number.required' => 'Le numéro BL est obligatoire.',
        'bl_number.unique' => 'Ce numéro BL existe déjà.',
        'bl_number.max' => 'Le numéro BL ne peut pas dépasser 12 caractères.',
        'nbr_conteneur.required' => 'Le nombre de conteneurs est obligatoire.',
        'nbr_conteneur.integer' => 'Le nombre de conteneurs doit être un nombre entier.',
        'nbr_conteneur.min' => 'Le nombre de conteneurs doit être au moins 1.',
        'nbr_conteneur.max' => 'Le nombre de conteneurs ne peut pas dépasser 999.',
        'ship_name.required' => 'Le nom du navire est obligatoire.',
        'ship_name.max' => 'Le nom du navire ne peut pas dépasser 100 caractères.',
        'type_of_conteneur.required' => 'Le type de conteneur est obligatoire.',
        'type_of_conteneur.max' => 'Le type de conteneur ne peut pas dépasser 50 caractères.',
        'category.max' => 'Les catégories ne peuvent pas dépasser 50 caractères.',
        'note.max' => 'La note ne peut pas dépasser 255 caractères.',
        'client_id.required' => 'Veuillez sélectionner un client.',
        'client_id.exists' => 'Le client sélectionné n\'existe pas.',
        'type_travail_id.required' => 'Veuillez sélectionner un type de travail.',
        'type_travail_id.exists' => 'Le type de travail sélectionné n\'existe pas.',
    ];

    /**
     * Initialisation du composant
     */
    public function mount()
    {
        $this->created_by = auth()->user()->id;
        $this->bl_clients = Client::orderBy('name_of_entreprise')->get();
        $this->bl_types = TypeTravail::orderBy('type_work')->get();
    }

    /**
     * Validation en temps réel lors de la modification des champs
     */
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    /**
     * Sauvegarde un nouveau BL dans la base de données
     */
    public function save()
    {
        $this->validate();

        try {
            $bl = BilsOflading::create([
                'type_travail_id'   => $this->type_travail_id,
                'client_id'         => $this->client_id,
                'created_by'        => $this->created_by,
                'bl_number'         => strtoupper(trim($this->bl_number)),
                'nbr_conteneur'     => $this->nbr_conteneur,
                'ship_name'         => ucwords(strtolower(trim($this->ship_name))),
                'type_of_conteneur' => trim($this->type_of_conteneur),
                'category'          => $this->category ? trim($this->category) : null,
                'note'              => $this->note ? trim($this->note) : null,
            ]);

            // Toast de succès
            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'BL n° ' . $this->bl_number . ' créé avec succès ✅'
            ]);

            // Réinitialiser le formulaire après succès
            $this->resetForm();
            
            // Redirection vers la liste des BL
            return redirect()->route('bl.index');
            
        } catch (\Exception $e) {
            // En cas d'erreur, afficher un toast d'erreur
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Erreur lors de la création du BL: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Réinitialise tous les champs du formulaire
     */
    public function resetForm()
    {
        $this->reset([
            'type_travail_id',
            'client_id', 
            'bl_number',
            'nbr_conteneur',
            'ship_name',
            'type_of_conteneur',
            'category',
            'note'
        ]);
        
        // Réinitialiser aussi les erreurs de validation
        $this->resetValidation();
    }

    /**
     * Rendu du composant
     */
    public function render()
    {
        return view('livewire.bl.create-bl');
    }
}
