<?php

namespace App\Livewire\TypeTravail;

use App\Models\TypeTravail;
use Livewire\Component;

class CreateTypeTravail extends Component
{
    public $type_work = '';
    public $description = '';

    /**
     * Règles de validation
     */
    protected $rules = [
        'type_work' => 'required|string|max:255|unique:type_travails,type_work',
        'description' => 'nullable|string|max:500',
    ];

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
     * Fonction pour sauvegarder un nouveau type de travail
     */
    public function save()
    {
        $this->validate();

        try {
            TypeTravail::create([
                'type_work' => $this->type_work,
                'description' => $this->description,
                'created_by' => auth()->id(),
            ]);

            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'Type de travail créé avec succès!'
            ]);

            return redirect()->route('type-travail.index');
        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Erreur lors de la création: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Fonction pour réinitialiser le formulaire
     */
    public function resetForm()
    {
        $this->reset(['type_work', 'description']);
        $this->resetErrorBag();
    }

    /**
     * Fonction de rendu
     */
    public function render()
    {
        return view('livewire.type-travail.create-type-travail');
    }
}