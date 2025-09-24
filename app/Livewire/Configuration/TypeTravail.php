<?php

namespace App\Livewire\Configuration;

use App\Models\TypeTravail as TypeTravailModel;
use Livewire\Component;
use Livewire\WithPagination;

class TypeTravail extends Component
{
    use WithPagination;

    // Propriétés du formulaire
    public $nom = '';
    public $description = '';
    public $actif = true;

    // Propriétés de gestion
    public $editing = false;
    public $editingId = null;
    public $search = '';
    public $sortField = 'nom';
    public $sortDirection = 'asc';

    // Règles de validation
    protected $rules = [
        'nom' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'actif' => 'boolean',
    ];

    // Messages de validation personnalisés
    protected $messages = [
        'nom.required' => 'Le nom du type de travail est obligatoire.',
        'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',
        'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
    ];

    /**
     * Initialisation du composant
     */
    public function mount()
    {
        $this->resetForm();
    }

    /**
     * Créer un nouveau type de travail
     */
    public function create()
    {
        $this->validate();

        TypeTravailModel::create([
            'nom' => $this->nom,
            'description' => $this->description,
            'actif' => $this->actif,
            'created_by' => auth()->id(),
        ]);

        session()->flash('success', 'Type de travail créé avec succès.');
        $this->resetForm();
    }

    /**
     * Éditer un type de travail existant
     */
    public function edit($id)
    {
        $typeTravail = TypeTravailModel::findOrFail($id);
        
        $this->editingId = $id;
        $this->nom = $typeTravail->nom;
        $this->description = $typeTravail->description;
        $this->actif = $typeTravail->actif;
        $this->editing = true;
    }

    /**
     * Sauvegarder les modifications
     */
    public function save()
    {
        $this->validate();

        $typeTravail = TypeTravailModel::findOrFail($this->editingId);
        $typeTravail->update([
            'nom' => $this->nom,
            'description' => $this->description,
            'actif' => $this->actif,
        ]);

        session()->flash('success', 'Type de travail modifié avec succès.');
        $this->resetForm();
    }

    /**
     * Supprimer un type de travail
     */
    public function delete($id)
    {
        try {
            $typeTravail = TypeTravailModel::findOrFail($id);
            $typeTravail->delete();
            session()->flash('success', 'Type de travail supprimé avec succès.');
        } catch (\Exception $e) {
            session()->flash('error', 'Impossible de supprimer ce type de travail. Il est peut-être utilisé dans des suivis.');
        }
    }

    /**
     * Basculer le statut actif/inactif
     */
    public function toggleActive($id)
    {
        $typeTravail = TypeTravailModel::findOrFail($id);
        $typeTravail->update(['actif' => !$typeTravail->actif]);
        
        $status = $typeTravail->actif ? 'activé' : 'désactivé';
        session()->flash('success', "Type de travail {$status} avec succès.");
    }

    /**
     * Trier par colonne
     */
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Réinitialiser le formulaire
     */
    public function resetForm()
    {
        $this->nom = '';
        $this->description = '';
        $this->actif = true;
        $this->editing = false;
        $this->editingId = null;
        $this->resetErrorBag();
    }

    /**
     * Annuler l'édition
     */
    public function cancel()
    {
        $this->resetForm();
    }

    /**
     * Mise à jour de la recherche
     */
    public function updatedSearch()
    {
        $this->resetPage();
    }

    /**
     * Rendu du composant
     */
    public function render()
    {
        $typeTravails = TypeTravailModel::query()
            ->when($this->search, function ($query) {
                $query->where('nom', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.configuration.type-travail', [
            'typeTravails' => $typeTravails,
        ]);
    }
}
