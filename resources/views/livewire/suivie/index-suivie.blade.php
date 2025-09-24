<div>
    {{-- Toast Container --}}
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <div class="space-y-6">
        {{-- En-tête de la page --}}
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Gestion des Suivies</h1>
                <p class="mt-2 text-gray-600">Suivez l'état de vos expéditions et livraisons</p>
            </div>
            @can('create_suivies')
                <a 
                    wire:navigate
                    href="{{ route('suivies.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span>Nouveau Suivie</span>
                </a>
            @endcan
        </div>

        {{-- Filtres et recherche --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                    <div class="relative">
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search"
                            placeholder="N° BL, client..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">État</label>
                    <select 
                        wire:model.live="filterEtat"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tous les états</option>
                        <option value="En attente">En attente</option>
                        <option value="En transit">En transit</option>
                        <option value="Livré">Livré</option>
                        <option value="Retardé">Retardé</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                    <select 
                        wire:model.live="filterStatut"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tous les statuts</option>
                        <option value="Traitement">Traitement</option>
                        <option value="Expédié">Expédié</option>
                        <option value="Terminé">Terminé</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Agent Transit</label>
                    <select 
                        wire:model.live="filterAgent"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tous les agents</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->nom }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Statistiques rapides --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-blue-50 rounded-lg p-4">
                <div class="flex items-center">
                    <flux:icon.truck class="size-8 text-blue-600" />
                    <div class="ml-3">
                        <p class="text-sm font-medium text-blue-600">Total Suivies</p>
                        <p class="text-2xl font-semibold text-blue-900">{{ $suivies->total() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-50 rounded-lg p-4">
                <div class="flex items-center">
                    <flux:icon.exclamation-triangle class="size-8 text-yellow-600" />
                    <div class="ml-3">
                        <p class="text-sm font-medium text-yellow-600">Alertes</p>
                        <p class="text-2xl font-semibold text-yellow-900">
                            {{ $suivies->where('necessite_alerte', true)->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-orange-50 rounded-lg p-4">
                <div class="flex items-center">
                    <flux:icon.cog class="size-8 text-orange-600" />
                    <div class="ml-3">
                        <p class="text-sm font-medium text-orange-600">En Traitement</p>
                        <p class="text-2xl font-semibold text-orange-900">
                            {{ $suivies->where('status', 'traitement')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-green-50 rounded-lg p-4">
                <div class="flex items-center">
                    <flux:icon.folder class="size-8 text-green-600" />
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-600">En Stockage</p>
                        <p class="text-2xl font-semibold text-green-900">
                            {{ $suivies->where('status', 'stockage')->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tableau des suivies --}}
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N° BL</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ETA</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jours Restants</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">État</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agent Transit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Créé par</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($suivies as $suivie)
                            <tr class="{{ $suivie->necessite_alerte ? 'bg-yellow-50' : 'hover:bg-gray-50' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($suivie->necessite_alerte)
                                            <svg class="w-4 h-4 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                        @endif
                                        <span class="font-medium text-gray-900">{{ $suivie->numero_bl }}</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                    <div>
                        <p class="font-medium text-gray-900">{{ $suivie->client->name_of_entreprise ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">{{ $suivie->client->email ?? '' }}</p>
                    </div>
                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm text-gray-900">
                        {{ $suivie->ETA ? $suivie->ETA->format('d/m/Y') : 'Non défini' }}
                    </span>
                </td>

                <td class="px-6 py-4 whitespace-nowrap">
                    @if($suivie->ETA)
                        @php
                            $joursRestants = $suivie->jours_restants;
                            $couleur = $joursRestants < 0 ? 'text-red-600' : 
                                      ($joursRestants <= 3 ? 'text-orange-600' : 'text-green-600');
                        @endphp
                        <span class="{{ $couleur }} font-medium">
                            @if($joursRestants < 0)
                                Retard de {{ abs($joursRestants) }} jour(s)
                            @elseif($joursRestants == 0)
                                Aujourd'hui
                            @else
                                {{ $joursRestants }} jour(s)
                            @endif
                        </span>
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $etatColor = $suivie->etat === 'Livré' ? 'bg-green-100 text-green-800' : 
                                                    ($suivie->etat === 'En transit' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800');
                                    @endphp
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $etatColor }}">
                                        {{ $suivie->etat }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statutColor = $suivie->statut === 'Traitement' ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800';
                                    @endphp
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statutColor }}">
                                        {{ $suivie->statut }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($suivie->agent_transit_id)
                                        <span class="text-sm text-gray-900">{{ $suivie->agentTransit->nom ?? 'N/A' }}</span>
                                    @else
                                        <select 
                                            wire:model.live="assignAgent.{{ $suivie->id }}"
                                            wire:change="assignAgentToSuivie({{ $suivie->id }}, $event.target.value)"
                                            class="text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">Assigner un agent</option>
                                            @foreach($agents as $agent)
                                                <option value="{{ $agent->id }}">{{ $agent->nom }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">{{ $suivie->creator->name ?? 'Utilisateur inconnu' }}</span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a 
                                            wire:navigate
                                            href="{{ route('suivies.show', $suivie->id) }}"
                                            class="text-blue-600 hover:text-blue-900 p-1 rounded transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        
                                        @can('edit_suivies')
                                        <a 
                                            wire:navigate
                                            href="{{ route('suivies.edit', $suivie->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900 p-1 rounded transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        @endcan
                                        
                                        @can('delete_suivies')
                                        <button 
                                            wire:click="deleteSuivie({{ $suivie->id }})"
                                            wire:confirm="Êtes-vous sûr de vouloir supprimer ce suivie ?"
                                            class="text-red-600 hover:text-red-900 p-1 rounded transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p class="text-lg font-medium">Aucun suivie trouvé</p>
                                        <p class="text-sm">Commencez par créer un nouveau suivie.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($suivies->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $suivies->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Script pour les toasts --}}
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('toast', (event) => {
                const toast = document.createElement('div');
                toast.className = `alert alert-${event.type} mb-2 p-4 rounded-lg shadow-lg`;
                toast.innerHTML = `
                    <div class="flex items-center">
                        <span class="mr-2">${event.type === 'success' ? '✓' : '⚠'}</span>
                        <span>${event.message}</span>
                        <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-lg">&times;</button>
                    </div>
                `;
                
                document.getElementById('toast-container').appendChild(toast);
                
                setTimeout(() => {
                    toast.remove();
                }, 5000);
            });
        });
    </script>
</div>
