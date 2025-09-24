<?php

namespace App\Livewire\Client;

use App\Models\Client;
use Livewire\Component;
use Livewire\WithPagination;

class IndexClient extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    protected $queryString = ['search'];

    /**
     * Réinitialiser la pagination lors de la recherche
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Supprimer un client
     */
    public function delete($clientId)
    {
        try {
            $client = Client::findOrFail($clientId);
            $client->delete();
            
            $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => 'Client supprimé avec succès!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'Erreur lors de la suppression du client.'
            ]);
        }
    }

    /**
     * Rendu du composant
     */
    public function render()
    {
        $clients = Client::query()
            ->when($this->search, function ($query) {
                $query->where('name_of_entreprise', 'like', '%' . $this->search . '%')
                      ->orWhere('name_contact', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.client.index-client', compact('clients'));
    }
}
