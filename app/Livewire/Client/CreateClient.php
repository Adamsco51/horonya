<?php

namespace App\Livewire\Client;

use App\Models\Client;
use Livewire\Component;

class CreateClient extends Component
{
    public $name_of_entreprise = '';
    public $name_contact = '';
    public $email = '';
    public $phone = '';
    public $adresse = '';
    public $country = '';

    /**
     * Règles de validation
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
            'name_contact.required' => 'Le nom du contact est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'phone.required' => 'Le numéro de téléphone est obligatoire.',
        ];
    }

    /**
     * Sauvegarder le nouveau client
     */
    public function save()
    {
        $this->validate();

        try {
            $client = Client::create([
                'name_of_entreprise' => $this->name_of_entreprise,
                'name_contact' => $this->name_contact,
                'email' => $this->email ?: null,
                'phone' => $this->phone,
                'adresse' => $this->adresse ?: null,
                'country' => $this->country ?: null,
            ]);

            $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => 'Client créé avec succès! (' . $client->name_of_entreprise . ')'
            ]);

            return $this->redirect(route('clients.index'), navigate: true);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'Erreur lors de la création du client.'
            ]);
        }
    }

    /**
     * Réinitialiser le formulaire
     */
    public function resetForm()
    {
        $this->reset([
            'name_of_entreprise',
            'name_contact',
            'email',
            'phone',
            'adresse',
            'country'
        ]);
        $this->resetValidation();
    }

    /**
     * Rendu du composant
     */
    public function render()
    {
        return view('livewire.client.create-client');
    }
}
