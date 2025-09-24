<?php

namespace App\Livewire\Client;

use App\Models\Client;
use Livewire\Component;

class ShowClient extends Component
{
    // Propriété pour stocker le client
    public $client;

    /**
     * Monter le composant avec les données du client
     */
    public function mount($id)
    {
        $this->client = Client::findOrFail($id);
    }

    /**
     * Supprimer le client
     */
    public function deleteClient()
    {
        try {
            $this->client->delete();

            $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => 'Client supprimé avec succès!'
            ]);

            // Rediriger vers la liste des clients
            $this->redirect(route('clients.index'), navigate: true);

        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'Erreur lors de la suppression du client: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Rendu du composant
     */
    public function render()
    {
        return view('livewire.client.show-client');
    }
}
