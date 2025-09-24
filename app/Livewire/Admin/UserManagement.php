<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\UserLoginLog;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserManagement extends Component
{
    use WithPagination;

    // Propriétés pour la gestion des utilisateurs
    public $name = '';
    public $email = '';
    public $password = '';
    public $selectedRoles = [];
    public $selectedPermissions = [];
    public $is_active = true;
    
    // Propriétés pour l'édition
    public $editingUserId = null;
    public $editingUser = null;
    
    // Propriétés pour la recherche et le tri
    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    
    // Propriétés pour les filtres
    public $filterRole = '';
    public $filterStatus = '';

    // Propriétés pour les logs de connexion
    public $showingLogsForUser = null;
    public $logsDateFilter = '';
    public $logsActionFilter = '';

    /**
     * Règles de validation
     */
    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'selectedRoles' => 'array',
            'selectedPermissions' => 'array',
            'is_active' => 'boolean',
        ];

        if ($this->editingUserId) {
            $rules['email'] = 'required|email|unique:users,email,' . $this->editingUserId;
            $rules['password'] = 'nullable|string|min:8';
        } else {
            $rules['email'] = 'required|email|unique:users,email';
            $rules['password'] = 'required|string|min:8';
        }

        return $rules;
    }

    /**
     * Messages de validation personnalisés
     */
    protected $messages = [
        'name.required' => 'Le nom est obligatoire.',
        'email.required' => 'L\'email est obligatoire.',
        'email.email' => 'L\'email doit être valide.',
        'email.unique' => 'Cet email est déjà utilisé.',
        'password.required' => 'Le mot de passe est obligatoire.',
        'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
    ];

    /**
     * Créer un nouvel utilisateur
     */
    public function createUser()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'is_active' => $this->is_active,
            'email_verified_at' => now(), // Auto-vérification par l'admin
        ]);

        // Assigner les rôles
        if (!empty($this->selectedRoles)) {
            $user->assignRole($this->selectedRoles);
        } else {
            // Assigner le rôle par défaut
            $user->assignRole('agent transit');
        }

        // Assigner les permissions supplémentaires
        if (!empty($this->selectedPermissions)) {
            $user->givePermissionTo($this->selectedPermissions);
        }

        session()->flash('message', 'Utilisateur créé avec succès.');
        $this->resetForm();
    }

    /**
     * Éditer un utilisateur
     */
    public function editUser($userId)
    {
        $this->editingUserId = $userId;
        $this->editingUser = User::with('roles', 'permissions')->findOrFail($userId);
        
        $this->name = $this->editingUser->name;
        $this->email = $this->editingUser->email;
        $this->is_active = $this->editingUser->is_active;
        $this->selectedRoles = $this->editingUser->roles->pluck('name')->toArray();
        $this->selectedPermissions = $this->editingUser->permissions->pluck('name')->toArray();
        $this->password = ''; // Ne pas pré-remplir le mot de passe
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function updateUser()
    {
        $this->validate();

        $user = User::findOrFail($this->editingUserId);
        
        $updateData = [
            'name' => $this->name,
            'email' => $this->email,
            'is_active' => $this->is_active,
        ];

        // Mettre à jour le mot de passe seulement s'il est fourni
        if (!empty($this->password)) {
            $updateData['password'] = Hash::make($this->password);
        }

        $user->update($updateData);

        // Synchroniser les rôles
        if (!empty($this->selectedRoles)) {
            $user->syncRoles($this->selectedRoles);
        } else {
            $user->syncRoles(['agent transit']); // Rôle par défaut
        }

        // Synchroniser les permissions
        $user->syncPermissions($this->selectedPermissions);

        session()->flash('message', 'Utilisateur mis à jour avec succès.');
        $this->cancelEdit();
    }

    /**
     * Supprimer un utilisateur
     */
    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);
        
        // Empêcher la suppression de son propre compte
        if ($user->id === auth()->id()) {
            session()->flash('error', 'Vous ne pouvez pas supprimer votre propre compte.');
            return;
        }

        $user->delete();
        session()->flash('message', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Activer/Désactiver un utilisateur
     */
    public function toggleUserStatus($userId)
    {
        $user = User::findOrFail($userId);
        
        // Empêcher la désactivation de son propre compte
        if ($user->id === auth()->id()) {
            session()->flash('error', 'Vous ne pouvez pas désactiver votre propre compte.');
            return;
        }

        $user->update(['is_active' => !$user->is_active]);
        
        $status = $user->is_active ? 'activé' : 'désactivé';
        session()->flash('message', "Utilisateur {$status} avec succès.");
    }

    /**
     * Afficher les logs de connexion d'un utilisateur
     */
    public function showUserLogs($userId)
    {
        $this->showingLogsForUser = $userId;
        $this->logsDateFilter = '';
        $this->logsActionFilter = '';
    }

    /**
     * Masquer les logs de connexion
     */
    public function hideUserLogs()
    {
        $this->showingLogsForUser = null;
        $this->logsDateFilter = '';
        $this->logsActionFilter = '';
    }

    /**
     * Obtenir les logs de connexion pour l'utilisateur sélectionné
     */
    public function getUserLogs()
    {
        if (!$this->showingLogsForUser) {
            return collect();
        }

        $query = UserLoginLog::with('user')
            ->where('user_id', $this->showingLogsForUser)
            ->orderBy('logged_at', 'desc')
            ->limit(100); // Limiter le nombre de résultats pour éviter les timeouts

        // Filtrer par date si spécifié
        if ($this->logsDateFilter) {
            $date = Carbon::parse($this->logsDateFilter);
            $query->whereDate('logged_at', $date);
        }

        // Filtrer par action si spécifié
        if ($this->logsActionFilter) {
            $query->where('action', $this->logsActionFilter);
        }

        return $query->paginate(20);
    }

    /**
     * Exporter les logs de connexion d'un utilisateur
     */
    public function exportUserLogs($userId)
    {
        $user = User::findOrFail($userId);
        $logs = UserLoginLog::where('user_id', $userId)
            ->orderBy('logged_at', 'desc')
            ->get();

        $filename = "logs_connexion_{$user->name}_{now()->format('Y-m-d')}.csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // En-têtes CSV
            fputcsv($file, [
                'Date/Heure',
                'Action',
                'Adresse IP',
                'Navigateur',
                'Plateforme',
                'Device',
                'Données supplémentaires'
            ]);

            // Données
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->logged_at->format('d/m/Y H:i:s'),
                    $log->action,
                    $log->ip_address,
                    $log->browser,
                    $log->platform,
                    $log->device,
                    $log->additional_data ? json_encode($log->additional_data) : ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Annuler l'édition
     */
    public function cancelEdit()
    {
        $this->editingUserId = null;
        $this->editingUser = null;
        $this->resetForm();
    }

    /**
     * Réinitialiser le formulaire
     */
    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->selectedRoles = [];
        $this->selectedPermissions = [];
        $this->is_active = true;
        $this->resetValidation();
    }

    /**
     * Trier les résultats
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
     * Mettre à jour la recherche
     */
    public function updatedSearch()
    {
        $this->resetPage();
    }

    /**
     * Mettre à jour les filtres
     */
    public function updatedFilterRole()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    /**
     * Rendu du composant
     */
    public function render()
    {
        $users = User::with('roles', 'permissions')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterRole, function ($query) {
                $query->whereHas('roles', function ($q) {
                    $q->where('name', $this->filterRole);
                });
            })
            ->when($this->filterStatus !== '', function ($query) {
                $query->where('is_active', $this->filterStatus);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        $roles = Role::all();
        $permissions = Permission::all();
        
        // Ne charger les logs que si un utilisateur est sélectionné
        $userLogs = $this->showingLogsForUser ? $this->getUserLogs() : collect();

        return view('livewire.admin.user-management', [
            'users' => $users,
            'roles' => $roles,
            'permissions' => $permissions,
            'userLogs' => $userLogs,
        ]);
    }
}
