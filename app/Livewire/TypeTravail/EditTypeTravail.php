<?php

namespace App\Livewire\TypeTravail;

use App\Models\TypeTravail;
use Livewire\Component;

class EditTypeTravail extends Component
{
    public TypeTravail $typeTravail;
    public $type_work = '';
    public $description = '';

    /**
     * Règles de validation
     */
    protected function rules()
    {
        return [
            'type_work' => 'required|string|max:255|unique:type_travails,type_work,' . $this->typeTravail->id,
            'description' => 'nullable|string|max:500',
        ];
    }

    /**
     * Messages de validation personnalisés
     */
    protected $messages = [
        'type_work.required' => 'Le type de travail est obligatoire.',
        'type_work.unique' => 'Ce type de travail existe déjà.',
        'type_work.max' => 'Le type de travail ne peut pas dépasser 255 caractères.',
        'description.max' => 'La description ne peut pas dépasser 500 caractères.',
    ];

    /**
     * Fonction de montage du composant
     */
    public function mount(TypeTravail $typeTravail)
    {
        $this->typeTravail = $typeTravail;
        $this->type_work = $typeTravail->type_work;
        $this->description = $typeTravail->description;
    }

    /**
     * Fonction pour mettre à jour le type de travail
     */
    public function update()
    {
        $this->validate();

        try {
            $this->typeTravail->update([
                'type_work' => $this->type_work,
                'description' => $this->description,
            ]);

            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'Type de travail mis à jour avec succès!'
            ]);

            return redirect()->route('type-travail.index');
        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Fonction pour réinitialiser le formulaire
     */
    public function resetForm()
    {
        $this->type_work = $this->typeTravail->type_work;
        $this->description = $this->typeTravail->description;
        $this->resetErrorBag();
    }

    /**
     * Fonction de rendu
     */
    public function render()
    {
        return view('livewire.type-travail.edit-type-travail');
    }
}