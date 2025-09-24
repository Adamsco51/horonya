<?php

namespace App\Livewire\Configuration;

use Livewire\Component;
use App\Models\EtapeTraitement as EtapeTraitementModel;
use Livewire\WithPagination;

/**
 * Composant Livewire pour la gestion des étapes de traitement
 * Permet de créer, modifier, supprimer et réorganiser les étapes
 */
class EtapeTraitement extends Component
{
    use WithPagination;

    // Propriétés pour le formulaire
    public $nom = '';
    public $description = '';
    public $ordre = 1;
    public $obligatoire = false;
    public $active = true;
    
    // Propriétés pour l'édition
    public $editingId = null;
    public $showForm = false;
    
    // Propriétés pour la recherche et le tri
    public $search = '';
    public $sortField = 'ordre';
    public $sortDirection = 'asc';

    /**
     * Règles de validation
     */
    protected function rules()
    {
        return [
            'nom' => 'required|string|max:255|unique:etape_traitements,nom,' . $this->editingId,
            'description' => 'nullable|string|max:1000',
            'ordre' => 'required|integer|min:1',
            'obligatoire' => 'boolean',
            'active' => 'boolean',
        ];
    }

    /**
     * Messages de validation personnalisés
     */
    protected function messages()
    {
        return [
            'nom.required' => 'Le nom de l\'étape est obligatoire.',
            'nom.unique' => 'Ce nom d\'étape existe déjà.',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
            'ordre.required' => 'L\'ordre est obligatoire.',
            'ordre.integer' => 'L\'ordre doit être un nombre entier.',
            'ordre.min' => 'L\'ordre doit être supérieur à 0.',
        ];
    }

    /**
     * Initialisation du composant
     */
    public function mount()
    {
        $this->resetForm();
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->ordre = $this->getNextOrdre();
    }

    /**
     * Éditer une étape existante
     */
    public function edit($id)
    {
        $etape = EtapeTraitementModel::findOrFail($id);
        
        $this->editingId = $id;
        $this->nom = $etape->nom;
        $this->description = $etape->description;
        $this->ordre = $etape->ordre;
        $this->obligatoire = $etape->obligatoire;
        $this->active = $etape->active;
        $this->showForm = true;
    }

    /**
     * Sauvegarder une étape (création ou modification)
     */
    public function save()
    {
        $this->validate();

        try {
            if ($this->editingId) {
                // Modification
                $etape = EtapeTraitementModel::findOrFail($this->editingId);
                $etape->update([
                    'nom' => $this->nom,
                    'description' => $this->description,
                    'ordre' => $this->ordre,
                    'obligatoire' => $this->obligatoire,
                    'active' => $this->active,
                ]);
                
                session()->flash('message', 'Étape modifiée avec succès.');
            } else {
                // Création
                EtapeTraitementModel::create([
                    'nom' => $this->nom,
                    'description' => $this->description,
                    'ordre' => $this->ordre,
                    'obligatoire' => $this->obligatoire,
                    'active' => $this->active,
                ]);
                
                session()->flash('message', 'Étape créée avec succès.');
            }

            $this->resetForm();
            $this->showForm = false;
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la sauvegarde : ' . $e->getMessage());
        }
    }

    /**
     * Supprimer une étape
     */
    public function delete($id)
    {
        try {
            $etape = EtapeTraitementModel::findOrFail($id);
            
            // Vérifier si l'étape est utilisée dans des suivis
            if ($etape->suiviTraitements()->count() > 0) {
                session()->flash('error', 'Impossible de supprimer cette étape car elle est utilisée dans des suivis.');
                return;
            }
            
            $etape->delete();
            session()->flash('message', 'Étape supprimée avec succès.');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    /**
     * Basculer le statut actif/inactif d'une étape
     */
    public function toggleActive($id)
    {
        try {
            $etape = EtapeTraitementModel::findOrFail($id);
            $etape->update(['active' => !$etape->active]);
            
            $status = $etape->active ? 'activée' : 'désactivée';
            session()->flash('message', "Étape {$status} avec succès.");
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors du changement de statut : ' . $e->getMessage());
        }
    }

    /**
     * Déplacer une étape vers le haut
     */
    public function moveUp($id)
    {
        $this->moveEtape($id, 'up');
    }

    /**
     * Déplacer une étape vers le bas
     */
    public function moveDown($id)
    {
        $this->moveEtape($id, 'down');
    }

    /**
     * Déplacer une étape dans l'ordre
     */
    private function moveEtape($id, $direction)
    {
        try {
            $etape = EtapeTraitementModel::findOrFail($id);
            $currentOrdre = $etape->ordre;
            
            if ($direction === 'up') {
                $targetEtape = EtapeTraitementModel::where('ordre', '<', $currentOrdre)
                    ->orderBy('ordre', 'desc')
                    ->first();
            } else {
                $targetEtape = EtapeTraitementModel::where('ordre', '>', $currentOrdre)
                    ->orderBy('ordre', 'asc')
                    ->first();
            }
            
            if ($targetEtape) {
                // Échanger les ordres
                $targetOrdre = $targetEtape->ordre;
                $targetEtape->update(['ordre' => $currentOrdre]);
                $etape->update(['ordre' => $targetOrdre]);
                
                session()->flash('message', 'Ordre des étapes modifié avec succès.');
            }
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors du déplacement : ' . $e->getMessage());
        }
    }

    /**
     * Trier les résultats
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
        $this->ordre = 1;
        $this->obligatoire = false;
        $this->active = true;
        $this->editingId = null;
        $this->showForm = false;
        $this->resetValidation();
    }

    /**
     * Annuler l'édition
     */
    public function cancel()
    {
        $this->resetForm();
    }

    /**
     * Obtenir le prochain numéro d'ordre
     */
    private function getNextOrdre()
    {
        return EtapeTraitementModel::max('ordre') + 1;
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
        $etapes = EtapeTraitementModel::query()
            ->when($this->search, function ($query) {
                $query->where('nom', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.configuration.etape-traitement', [
            'etapes' => $etapes,
        ]);
    }
}
