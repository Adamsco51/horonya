<div>
    {{-- Toast Container --}}
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <div class="max-w-6xl mx-auto space-y-6">
        {{-- En-tête --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Détails du Suivie</h1>
                <p class="text-sm text-gray-600">{{ $suivie->numero_bl }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <button 
                    wire:click="updateStatutAutomatique"
                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Actualiser
                </button>
                <a href="{{ route('suivies.edit', $suivie) }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Modifier
                </a>
                <a href="{{ route('suivies.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour
                </a>
            </div>
        </div>

        {{-- Carte d'alerte rouge si ETA <= 7 jours --}}
        @if($suivie->jours_restants <= 7 && $suivie->jours_restants >= 0)
            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            Alerte - Navire arrive bientôt !
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p>
                                @if($suivie->jours_restants == 0)
                                    Le navire arrive <strong>aujourd'hui</strong>. Préparez les opérations de déchargement.
                                @else
                                    Il ne reste que <strong>{{ $suivie->jours_restants }} jour(s)</strong> avant l'arrivée du navire.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($suivie->jours_restants < 0)
            <div class="bg-red-100 border-l-4 border-red-500 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            Navire en retard !
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p>Le navire est en retard de <strong>{{ abs($suivie->jours_restants) }} jour(s)</strong>.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Informations principales --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Détails du suivie --}}
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Informations du Suivie</h3>
                    </div>
                    <div class="px-6 py-4">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Numéro BL</dt>
                                <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $suivie->numero_bl }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">ETA</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $suivie->ETA ? $suivie->ETA->format('d/m/Y') : 'Non défini' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Jours restants</dt>
                                <dd class="mt-1">
                                    @php
                                        $joursRestants = $suivie->jours_restants;
                                        $couleur = $joursRestants < 0 ? 'text-red-600' : 
                                                  ($joursRestants <= 7 ? 'text-orange-600' : 'text-green-600');
                                    @endphp
                                    <span class="{{ $couleur }} font-medium">
                                        @if($joursRestants < 0)
                                            Retard de {{ abs($joursRestants) }} jour(s)
                                        @elseif($joursRestants == 0)
                                            Arrive aujourd'hui
                                        @else
                                            {{ $joursRestants }} jour(s)
                                        @endif
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">État</dt>
                                <dd class="mt-1">
                                    @php
                                        $etatColor = match($suivie->etat) {
                                            'arrive' => 'bg-green-100 text-green-800',
                                            'retard' => 'bg-red-100 text-red-800',
                                            'en_cours' => 'bg-blue-100 text-blue-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                    @endphp
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $etatColor }}">
                                        {{ ucfirst(str_replace('_', ' ', $suivie->etat)) }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Statut</dt>
                                <dd class="mt-1">
                                    @php
                                        $statusColor = match($suivie->status) {
                                            'traitement' => 'bg-orange-100 text-orange-800',
                                            'stockage' => 'bg-blue-100 text-blue-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                    @endphp
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColor }}">
                                        {{ ucfirst($suivie->status) }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Agent de transit</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $suivie->agentTransit->name ?? 'Non assigné' }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                {{-- Informations complètes du client --}}
                @if($suivie->client)
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Informations du Client</h3>
                    </div>
                    <div class="px-6 py-4">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nom</dt>
                                <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $suivie->client->nom }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if($suivie->client->email)
                                        <a href="mailto:{{ $suivie->client->email }}" class="text-blue-600 hover:text-blue-800">
                                            {{ $suivie->client->email }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">Non renseigné</span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Téléphone</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if($suivie->client->telephone)
                                        <a href="tel:{{ $suivie->client->telephone }}" class="text-blue-600 hover:text-blue-800">
                                            {{ $suivie->client->telephone }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">Non renseigné</span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Adresse</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $suivie->client->adresse ?? 'Non renseignée' }}
                                </dd>
                            </div>
                            @if($suivie->client->ville || $suivie->client->code_postal)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Ville / Code postal</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $suivie->client->ville ?? '' }} {{ $suivie->client->code_postal ?? '' }}
                                </dd>
                            </div>
                            @endif
                            @if($suivie->client->pays)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Pays</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $suivie->client->pays }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>
                @endif

                {{-- Informations complètes du BL --}}
                @if($suivie->bilsOflading)
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Informations du Bill of Lading</h3>
                    </div>
                    <div class="px-6 py-4">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Numéro BL</dt>
                                <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $suivie->bilsOflading->bl_number }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nom du navire</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $suivie->bilsOflading->ship_name ?? 'Non renseigné' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nombre de conteneurs</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $suivie->bilsOflading->nbr_conteneur ?? 'Non renseigné' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Type de conteneur</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $suivie->bilsOflading->type_of_conteneur ?? 'Non renseigné' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Catégorie</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $suivie->bilsOflading->category ?? 'Non renseignée' }}</dd>
                            </div>
                            @if($suivie->bilsOflading->note)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Notes</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $suivie->bilsOflading->note }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>
                @endif
            </div>

            {{-- Sidebar avec actions et métadonnées --}}
            <div class="space-y-6">
                {{-- Actions rapides --}}
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Actions</h3>
                    </div>
                    <div class="px-6 py-4 space-y-3">
                        <button 
                            wire:click="updateStatutAutomatique"
                            class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Mettre à jour le statut
                        </button>
                        
                        <button 
                            wire:click="delete"
                            wire:confirm="Êtes-vous sûr de vouloir supprimer ce suivie ?"
                            class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Supprimer
                        </button>
                    </div>
                </div>

                {{-- Métadonnées --}}
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Métadonnées</h3>
                    </div>
                    <div class="px-6 py-4">
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Créé par</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $suivie->creator->name ?? 'Système' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date de création</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $suivie->created_at->format('d/m/Y à H:i') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Dernière modification</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $suivie->updated_at->format('d/m/Y à H:i') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
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
