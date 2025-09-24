<?php

namespace App\Livewire\BL;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BilsOflading;
use App\Models\Client;
use App\Models\TypeTravail;

class IndexBL extends Component
{
    use WithPagination;

    public $search = '';
    public $clientFilter = '';
    public $dateFilter = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'clientFilter' => ['except' => ''],
        'dateFilter' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 10],
    ];

    /**
     * Réinitialiser la pagination lors de la recherche
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Réinitialiser la pagination lors du filtrage par client
     */
    public function updatingClientFilter()
    {
        $this->resetPage();
    }

    /**
     * Réinitialiser la pagination lors du filtrage par date
     */
    public function updatingDateFilter()
    {
        $this->resetPage();
    }

    /**
     * Réinitialiser tous les filtres
     */
    public function resetFilters()
    {
        $this->search = '';
        $this->clientFilter = '';
        $this->dateFilter = '';
        $this->resetPage();
    }

    /**
     * Trier les résultats par un champ donné
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
     * Supprimer un BL avec confirmation
     */
    public function deleteBL($id)
    {
        $bl = BilsOflading::findOrFail($id);
        $bl->delete();
        
        session()->flash('message', 'BL supprimé avec succès.');
        $this->resetPage();
    }

    /**
     * Confirmer la suppression d'un BL
     */
    public function confirmDelete($id)
    {
        $this->dispatch('confirm-delete', $id);
    }

    public function render()
    {
        // Récupérer tous les clients pour le filtre
        $clients = Client::orderBy('name_of_entreprise')->get();

        $bls = BilsOflading::with(['client', 'type_travail', 'creator'])
            ->when($this->search, function ($query) {
                $query->where('bl_number', 'like', '%' . $this->search . '%')
                      ->orWhere('ship_name', 'like', '%' . $this->search . '%')
                      ->orWhere('type_of_conteneur', 'like', '%' . $this->search . '%')
                      ->orWhereHas('client', function ($q) {
                          $q->where('name_of_entreprise', 'like', '%' . $this->search . '%')
                            ->orWhere('name_contact', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('type_travail', function ($q) {
                          $q->where('type_work', 'like', '%' . $this->search . '%');
                      });
            })
            ->when($this->clientFilter, function ($query) {
                $query->whereHas('client', function ($q) {
                    $q->where('id', $this->clientFilter);
                });
            })
            ->when($this->dateFilter, function ($query) {
                $query->whereDate('created_at', $this->dateFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.bl.index-bl', compact('bls', 'clients'));
    }
}
