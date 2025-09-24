<?php

namespace App\Livewire\BL;

use Livewire\Component;
use App\Models\BilsOflading;

class ShowBL extends Component
{
    public $bl;

    /**
     * Monter le composant avec les données du BL à afficher
     */
    public function mount($id)
    {
        $this->bl = BilsOflading::with(['client', 'type_travail', 'creator'])
            ->findOrFail($id);
    }

    /**
     * Supprimer le BL avec confirmation
     */
    public function deleteBL()
    {
        $this->bl->delete();
        session()->flash('message', 'BL supprimé avec succès.');
        return redirect()->route('bl.index');
    }

    /**
     * Confirmer la suppression
     */
    public function confirmDelete()
    {
        $this->dispatch('confirm-delete');
    }

    public function render()
    {
        return view('livewire.bl.show-bl');
    }
}
