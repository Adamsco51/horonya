<?php

namespace App\Livewire\TypeTravail;

use App\Models\TypeTravail;
use Livewire\Component;
use Livewire\WithPagination;

class IndexTypeTravail extends Component
{
    use WithPagination;

    public $search = '';

    /**
     * Fonction pour rechercher les types de travail
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Fonction pour supprimer un type de travail
     */
    public function deleteTypeTravail($id)
    {
        try {
            $typeTravail = TypeTravail::findOrFail($id);
            $typeTravail->delete();
            
            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'Type de travail supprimé avec succès!'
            ]);
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
        $typeTravails = TypeTravail::where('type_work', 'like', '%' . $this->search . '%')
            ->orWhere('description', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.type-travail.index-type-travail', compact('typeTravails'));
    }
}