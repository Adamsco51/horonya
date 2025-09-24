<div>
    <x-layouts.app title="Gestion des Utilisateurs">
        <div class="space-y-6">
            <!-- Messages de feedback -->
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

            <!-- En-tête avec bouton d'ajout -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Gestion des Utilisateurs</h1>
                    <p class="text-gray-600 dark:text-gray-400">Gérez les utilisateurs, leurs rôles et permissions</p>
                </div>
                @if(!$editingUserId)
                    @can('create_users')
                    <flux:button wire:click="resetForm" variant="primary">
                        <flux:icon.plus class="w-4 h-4 mr-2" />
                        Nouvel Utilisateur
                    </flux:button>
                    @endcan
                @endif
            </div>

            <!-- Formulaire de création/édition -->
            @if($editingUserId || $name || $email)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">
                        {{ $editingUserId ? 'Modifier l\'utilisateur' : 'Créer un utilisateur' }}
                    </h3>
                    
                    <form wire:submit.prevent="{{ $editingUserId ? 'updateUser' : 'createUser' }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Nom -->
                            <div>
                                <flux:field>
                                    <flux:label>Nom complet</flux:label>
                                    <flux:input wire:model="name" placeholder="Nom complet" />
                                    <flux:error name="name" />
                                </flux:field>
                            </div>

                            <!-- Email -->
                            <div>
                                <flux:field>
                                    <flux:label>Email</flux:label>
                                    <flux:input type="email" wire:model="email" placeholder="email@example.com" />
                                    <flux:error name="email" />
                                </flux:field>
                            </div>

                            <!-- Mot de passe -->
                            <div>
                                <flux:field>
                                    <flux:label>{{ $editingUserId ? 'Nouveau mot de passe (optionnel)' : 'Mot de passe' }}</flux:label>
                                    <flux:input type="password" wire:model="password" placeholder="••••••••" />
                                    <flux:error name="password" />
                                </flux:field>
                            </div>

                            <!-- Statut actif -->
                            <div class="flex items-center">
                                <flux:checkbox wire:model="is_active" />
                                <flux:label class="ml-2">Compte actif</flux:label>
                            </div>
                        </div>

                        <!-- Rôles -->
                        <div>
                            <flux:label>Rôles</flux:label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mt-2">
                                @foreach($roles as $role)
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" wire:model="selectedRoles" value="{{ $role->name }}" 
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="text-sm text-gray-700 dark:text-gray-300 capitalize">{{ $role->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Permissions supplémentaires -->
                        <div>
                            <flux:label>Permissions supplémentaires</flux:label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2 mt-2 max-h-40 overflow-y-auto">
                                @foreach($permissions as $permission)
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->name }}" 
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="text-xs text-gray-600 dark:text-gray-400">{{ str_replace('_', ' ', $permission->name) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="flex space-x-3">
                            <flux:button type="submit" variant="primary">
                                {{ $editingUserId ? 'Mettre à jour' : 'Créer' }}
                            </flux:button>
                            <flux:button type="button" wire:click="cancelEdit" variant="ghost">
                                Annuler
                            </flux:button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Filtres et recherche -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Recherche -->
                    <div>
                        <flux:field>
                            <flux:label>Rechercher</flux:label>
                            <flux:input wire:model.live="search" placeholder="Nom ou email..." />
                        </flux:field>
                    </div>

                    <!-- Filtre par rôle -->
                    <div>
                        <flux:field>
                            <flux:label>Filtrer par rôle</flux:label>
                            <flux:select wire:model.live="filterRole">
                                <option value="">Tous les rôles</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </flux:select>
                        </flux:field>
                    </div>

                    <!-- Filtre par statut -->
                    <div>
                        <flux:field>
                            <flux:label>Filtrer par statut</flux:label>
                            <flux:select wire:model.live="filterStatus">
                                <option value="">Tous les statuts</option>
                                <option value="1">Actifs</option>
                                <option value="0">Inactifs</option>
                            </flux:select>
                        </flux:field>
                    </div>
                </div>
            </div>

            <!-- Liste des utilisateurs -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left">
                                    <button wire:click="sortBy('name')" class="flex items-center space-x-1 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider hover:text-gray-700 dark:hover:text-gray-200">
                                        <span>Nom</span>
                                        @if($sortField === 'name')
                                            <flux:icon.chevron-up class="w-3 h-3 {{ $sortDirection === 'desc' ? 'rotate-180' : '' }}" />
                                        @endif
                                    </button>
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <button wire:click="sortBy('email')" class="flex items-center space-x-1 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider hover:text-gray-700 dark:hover:text-gray-200">
                                        <span>Email</span>
                                        @if($sortField === 'email')
                                            <flux:icon.chevron-up class="w-3 h-3 {{ $sortDirection === 'desc' ? 'rotate-180' : '' }}" />
                                        @endif
                                    </button>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Rôles
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Statut
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <button wire:click="sortBy('created_at')" class="flex items-center space-x-1 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider hover:text-gray-700 dark:hover:text-gray-200">
                                        <span>Créé le</span>
                                        @if($sortField === 'created_at')
                                            <flux:icon.chevron-up class="w-3 h-3 {{ $sortDirection === 'desc' ? 'rotate-180' : '' }}" />
                                        @endif
                                    </button>
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($users as $user)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                                                    <span class="text-white font-medium text-sm">{{ $user->initials() }}</span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $user->name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">{{ $user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($user->roles as $role)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                    {{ ucfirst($role->name) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($user->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                Actif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                Inactif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $user->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <!-- Bouton Logs de connexion -->
                                            @can('view_users')
                                            <flux:button wire:click="showUserLogs({{ $user->id }})" size="sm" variant="outline">
                                                <flux:icon.clock class="w-4 h-4" />
                                            </flux:button>
                                            @endcan

                                            <!-- Bouton Activer/Désactiver -->
                                            @if($user->id !== auth()->id())
                                                @can('manage_users')
                                                <flux:button 
                                                    wire:click="toggleUserStatus({{ $user->id }})" 
                                                    size="sm" 
                                                    variant="{{ $user->is_active ? 'danger' : 'primary' }}"
                                                    wire:confirm="Êtes-vous sûr de vouloir {{ $user->is_active ? 'désactiver' : 'activer' }} cet utilisateur ?">
                                                    {{ $user->is_active ? 'Désactiver' : 'Activer' }}
                                                </flux:button>
                                                @endcan
                                            @endif

                                            <!-- Bouton Éditer -->
                                            @can('edit_users')
                                            <flux:button wire:click="editUser({{ $user->id }})" size="sm" variant="ghost">
                                                <flux:icon.pencil class="w-4 h-4" />
                                            </flux:button>
                                            @endcan

                                            <!-- Bouton Supprimer -->
                                            @if($user->id !== auth()->id())
                                                @can('delete_users')
                                                <flux:button 
                                                    wire:click="deleteUser({{ $user->id }})" 
                                                    size="sm" 
                                                    variant="danger"
                                                    wire:confirm="Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.">
                                                    <flux:icon.trash class="w-4 h-4" />
                                                </flux:button>
                                                @endcan
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        Aucun utilisateur trouvé.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700">
                    {{ $users->links() }}
                </div>
            </div>

            <!-- Modal des logs de connexion -->
            @if($showingLogsForUser)
                <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="hideUserLogs"></div>
                        
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        
                        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="w-full">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4" id="modal-title">
                                            Logs de connexion - {{ $showingLogsForUser->name }}
                                        </h3>
                                        
                                        <!-- Filtres des logs -->
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                            <div>
                                                <flux:field>
                                                    <flux:label>Période</flux:label>
                                                    <flux:select wire:model.live="logsDateFilter">
                                                        <option value="">Toutes les dates</option>
                                                        <option value="today">Aujourd'hui</option>
                                                        <option value="week">Cette semaine</option>
                                                        <option value="month">Ce mois</option>
                                                        <option value="3months">3 derniers mois</option>
                                                    </flux:select>
                                                </flux:field>
                                            </div>
                                            
                                            <div>
                                                <flux:field>
                                                    <flux:label>Action</flux:label>
                                                    <flux:select wire:model.live="logsActionFilter">
                                                        <option value="">Toutes les actions</option>
                                                        <option value="login">Connexions</option>
                                                        <option value="logout">Déconnexions</option>
                                                        <option value="failed_login">Tentatives échouées</option>
                                                    </flux:select>
                                                </flux:field>
                                            </div>
                                            
                                            <div class="flex items-end">
                                                <flux:button wire:click="exportUserLogs" variant="outline" size="sm">
                                                    <flux:icon.arrow-down-tray class="w-4 h-4 mr-2" />
                                                    Exporter CSV
                                                </flux:button>
                                            </div>
                                        </div>
                                        
                                        <!-- Table des logs -->
                                        <div class="overflow-x-auto max-h-96">
                                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                                <thead class="bg-gray-50 dark:bg-gray-700">
                                                    <tr>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                            Date/Heure
                                                        </th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                            Action
                                                        </th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                            IP
                                                        </th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                            Appareil
                                                        </th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                            Navigateur
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                    @forelse($userLogs as $log)
                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                                {{ $log->logged_at->format('d/m/Y H:i:s') }}
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                @if($log->action === 'login')
                                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                                        Connexion
                                                                    </span>
                                                                @elseif($log->action === 'logout')
                                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                                        Déconnexion
                                                                    </span>
                                                                @else
                                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                                        Échec
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                {{ $log->ip_address }}
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                {{ $log->device ?? 'Inconnu' }}
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                {{ $log->browser ?? 'Inconnu' }}
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                                                Aucun log trouvé pour cette période.
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <flux:button wire:click="hideUserLogs" variant="ghost">
                                    Fermer
                                </flux:button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </x-layouts.app>
</div>
