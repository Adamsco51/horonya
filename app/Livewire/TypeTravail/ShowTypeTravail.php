<?php

namespace App\Livewire\TypeTravail;

use App\Models\TypeTravail;
use Livewire\Component;

class ShowTypeTravail extends Component
{
    public TypeTravail $typeTravail;

    /**
     * Fonction de montage du composant
     */
    public function mount(TypeTravail $typeTravail)
    {
        $this->typeTravail = $typeTravail;
    }

    /**
     * Fonction pour supprimer le type de travail
     */
    public function deleteTypeTravail()
    {
        try {
            $this->typeTravail->delete();
            
            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'Type de travail supprimé avec succès!'
            ]);

            return redirect()->route('type-travail.index');
        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Fonction de rendu
     */
    public function render()
    {
        return view('livewire.type-travail.show-type-travail');
    }
}