<?php

namespace App\Livewire\BL;

use Livewire\Component;
use App\Models\BilsOflading;
use App\Models\Client;
use App\Models\TypeTravail;

class EditBL extends Component
{
    public $blId;
    public $type_travail_id;
    public $client_id;
    public $bl_number;
    public $nbr_conteneur;
    public $ship_name;
    public $type_of_conteneur;
    public $category;
    public $note;
    public $bl_clients = [];
    public $bl_types = [];

    protected $rules = [
        'bl_number'          => 'required|string|max:50',
        'nbr_conteneur'      => 'required|integer|min:1',
        'ship_name'          => 'required|string|max:100',
        'type_of_conteneur'  => 'required|string|max:50',
        'category'           => 'nullable|string|max:50',
        'note'               => 'nullable|string|max:255',
        'client_id'          => 'required|integer|exists:clients,id',
        'type_travail_id'    => 'required|integer|exists:type_travails,id',
    ];

    /**
     * Monter le composant avec les données du BL à modifier
     */
    public function mount($id)
    {
        $this->blId = $id;
        $bl = BilsOflading::findOrFail($id);
        
        $this->type_travail_id = $bl->type_travail_id;
        $this->client_id = $bl->client_id;
        $this->bl_number = $bl->bl_number;
        $this->nbr_conteneur = $bl->nbr_conteneur;
        $this->ship_name = $bl->ship_name;
        $this->type_of_conteneur = $bl->type_of_conteneur;
        $this->category = $bl->category;
        $this->note = $bl->note;

        $this->loadSelectData();
    }

    /**
     * Charger les données pour les selects
     */
    private function loadSelectData()
    {
        $this->bl_clients = Client::all();
        $this->bl_types = TypeTravail::all();
    }

    /**
     * Valider les règles personnalisées pour la modification
     */
    protected function rules()
    {
        return [
            'bl_number'          => 'required|string|max:50|unique:bils_ofladings,bl_number,' . $this->blId,
            'nbr_conteneur'      => 'required|integer|min:1',
            'ship_name'          => 'required|string|max:100',
            'type_of_conteneur'  => 'required|string|max:50',
            'category'           => 'nullable|string|max:50',
            'note'               => 'nullable|string|max:255',
            'client_id'          => 'required|integer|exists:clients,id',
            'type_travail_id'    => 'required|integer|exists:type_travails,id',
        ];
    }

    /**
     * Sauvegarder les modifications du BL
     */
    public function save()
    {
        $this->validate();

        $bl = BilsOflading::findOrFail($this->blId);
        
        $bl->update([
            'type_travail_id'    => $this->type_travail_id,
            'client_id'          => $this->client_id,
            'bl_number'          => $this->bl_number,
            'nbr_conteneur'      => $this->nbr_conteneur,
            'ship_name'          => $this->ship_name,
            'type_of_conteneur'  => $this->type_of_conteneur,
            'category'           => $this->category,
            'note'               => $this->note,
        ]);

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'BL modifié avec succès.'
        ]);
        return redirect()->route('bl.index');
    }

    /**
     * Réinitialiser le formulaire avec les données originales
     */
    public function resetForm()
    {
        $this->mount($this->blId);
    }

    /**
     * Annuler et retourner à la liste
     */
    public function cancel()
    {
        return redirect()->route('bl.index');
    }

    public function render()
    {
        return view('livewire.bl.edit-bl');
    }
}
