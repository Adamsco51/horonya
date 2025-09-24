<?php

namespace App\Livewire\Client;

use App\Models\Client;
use Livewire\Component;

class EditClient extends Component
{
    // Propriétés du client
    public $clientId;
    public $name_of_entreprise = '';
    public $name_contact = '';
    public $email = '';
    public $phone = '';
    public $adresse = '';
    public $country = '';

    /**
     * Règles de validation pour l'édition d'un client
     */
    protected function rules()
    {
        return [
            'name_of_entreprise' => 'required|string|max:255',
            'name_contact' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'adresse' => 'nullable|string|max:500',
            'country' => 'nullable|string|max:100',
        ];
    }

    /**
     * Messages de validation personnalisés
     */
    protected function messages()
    {
        return [
            'name_of_entreprise.required' => 'Le nom de l\'entreprise est obligatoire.',
            'name_of_entreprise.max' => 'Le nom de l\'entreprise ne peut pas dépasser 255 caractères.',
            'name_contact.required' => 'Le nom du contact est obligatoire.',
            'name_contact.max' => 'Le nom du contact ne peut pas dépasser 255 caractères.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.max' => 'L\'adresse email ne peut pas dépasser 255 caractères.',
            'phone.required' => 'Le numéro de téléphone est obligatoire.',
            'phone.max' => 'Le numéro de téléphone ne peut pas dépasser 20 caractères.',
            'adresse.max' => 'L\'adresse ne peut pas dépasser 500 caractères.',
            'country.max' => 'Le pays ne peut pas dépasser 100 caractères.',
        ];
    }

    /**
     * Monter le composant avec les données du client
     */
    public function mount($id)
    {
        $this->clientId = $id;
        $client = Client::findOrFail($id);
        
        $this->name_of_entreprise = $client->name_of_entreprise;
        $this->name_contact = $client->name_contact;
        $this->email = $client->email;
        $this->phone = $client->phone;
        $this->adresse = $client->adresse;
        $this->country = $client->country;
    }

    /**
     * Mettre à jour le client
     */
    public function update()
    {
        $this->validate();

        try {
            $client = Client::findOrFail($this->clientId);
            
            $client->update([
                'name_of_entreprise' => $this->name_of_entreprise,
                'name_contact' => $this->name_contact,
                'email' => $this->email,
                'phone' => $this->phone,
                'adresse' => $this->adresse,
                'country' => $this->country,
            ]);

            $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => 'Client mis à jour avec succès!'
            ]);

            // Rediriger vers la liste des clients après 1 seconde
            $this->redirect(route('clients.index'), navigate: true);

        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'Erreur lors de la mise à jour du client: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Réinitialiser le formulaire avec les données originales
     */
    public function resetForm()
    {
        $client = Client::findOrFail($this->clientId);
        
        $this->name_of_entreprise = $client->name_of_entreprise;
        $this->name_contact = $client->name_contact;
        $this->email = $client->email;
        $this->phone = $client->phone;
        $this->adresse = $client->adresse;
        $this->country = $client->country;
    }

    /**
     * Rendu du composant
     */
    public function render()
    {
        return view('livewire.client.edit-client');
    }
}
