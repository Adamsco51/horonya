<div class="space-y-6">
    {{-- Messages de feedback --}}
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    {{-- En-tête avec informations principales --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex justify-between items-start">
            <div class="flex-1">
                <div class="flex items-center space-x-4 mb-4">
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                        Suivi {{ $suivie->bl->numero_bl }}
                    </h1>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $this->statutClass }}">
                        {{ $this->statutLabel }}
                    </span>
                    @if($suivie->eta)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $this->alerteEtaClass }}">
                            @if($this->joursRestants !== null)
                                @if($this->joursRestants > 0)
                                    {{ $this->joursRestants }} jour(s) restant(s)
                                @elseif($this->joursRestants == 0)
                                    Aujourd'hui
                                @else
                                    Retard de {{ abs($this->joursRestants) }} jour(s)
                                @endif
                            @endif
                        </span>
                    @endif
                </div>

                {{-- Barre de progression --}}
                @if($suivie->pourcentage_progression !== null)
                    <div class="mb-4">
                        <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-1">
                            <span>Progression du traitement</span>
                            <span>{{ $suivie->pourcentage_progression }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                                 style="width: {{ $suivie->pourcentage_progression }}%"></div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="flex space-x-3">
                <button wire:click="openTraitementModal" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Ajouter Traitement
                </button>

                {{-- Dropdown pour changer le statut --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Changer Statut
                        <svg class="ml-2 -mr-1 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false" 
                         class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5 z-10">
                        <div class="py-1">
                            <button wire:click="updateStatut('en_attente')" @click="open = false"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">
                                En Attente
                            </button>
                            <button wire:click="updateStatut('en_cours')" @click="open = false"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">
                                En Cours
                            </button>
                            <button wire:click="updateStatut('termine')" @click="open = false"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">
                                Terminé
                            </button>
                            <button wire:click="updateStatut('bloque')" @click="open = false"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">
                                Bloqué
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Informations détaillées --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Informations du BL --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informations du BL</h2>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Numéro BL:</span>
                    <span class="text-sm text-gray-900 dark:text-white">{{ $suivie->bl->numero_bl }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Client:</span>
                    <span class="text-sm text-gray-900 dark:text-white">{{ $suivie->bl->client->nom }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Type de Travail:</span>
                    <span class="text-sm text-gray-900 dark:text-white">{{ $suivie->bl->typeTravail->nom }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Port de Chargement:</span>
                    <span class="text-sm text-gray-900 dark:text-white">{{ $suivie->bl->port_chargement ?: 'Non spécifié' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Port de Déchargement:</span>
                    <span class="text-sm text-gray-900 dark:text-white">{{ $suivie->bl->port_dechargement ?: 'Non spécifié' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Date de Création:</span>
                    <span class="text-sm text-gray-900 dark:text-white">{{ $suivie->bl->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>

        {{-- Informations du Suivi --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informations du Suivi</h2>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Agent de Transit:</span>
                    <span class="text-sm text-gray-900 dark:text-white">{{ $suivie->agentTransit->nom ?? 'Non assigné' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">ETA:</span>
                    <span class="text-sm text-gray-900 dark:text-white">
                        {{ $suivie->eta ? $suivie->eta->format('d/m/Y') : 'Non définie' }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Localisation:</span>
                    <span class="text-sm text-gray-900 dark:text-white">{{ $suivie->localisation ?: 'Non spécifiée' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Créé par:</span>
                    <span class="text-sm text-gray-900 dark:text-white">{{ $suivie->creator->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Date de Création:</span>
                    <span class="text-sm text-gray-900 dark:text-white">{{ $suivie->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Dernière Mise à Jour:</span>
                    <span class="text-sm text-gray-900 dark:text-white">{{ $suivie->updated_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Observations --}}
    @if($suivie->observations)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Observations</h2>
            <div class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $suivie->observations }}</div>
        </div>
    @endif

    {{-- Historique des traitements --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Historique des Traitements</h2>
        </div>

        @if($suiviTraitements->count() > 0)
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($suiviTraitements as $traitement)
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $traitement->etapeTraitement->nom }}
                                    </h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($traitement->statut === 'termine') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif($traitement->statut === 'en_cours') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                        @elseif($traitement->statut === 'bloque') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                        @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $traitement->statut)) }}
                                    </span>
                                </div>

                                @if($traitement->commentaire)
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ $traitement->commentaire }}</p>
                                @endif

                                <div class="flex items-center space-x-4 text-xs text-gray-500 dark:text-gray-400">
                                    <span>Par {{ $traitement->user->name }}</span>
                                    @if($traitement->date_realisation)
                                        <span>Réalisé le {{ $traitement->date_realisation->format('d/m/Y H:i') }}</span>
                                    @endif
                                    <span>Créé le {{ $traitement->created_at->format('d/m/Y H:i') }}</span>
                                </div>

                                {{-- Documents joints --}}
                                @if($traitement->documents && count($traitement->documents) > 0)
                                    <div class="mt-3">
                                        <h4 class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Documents joints:</h4>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($traitement->documents as $document)
                                                <button wire:click="downloadDocument({{ $traitement->id }}, '{{ $document }}')"
                                                        class="inline-flex items-center px-2 py-1 border border-gray-300 dark:border-gray-600 rounded text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    {{ basename($document) }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Aucun traitement enregistré</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Commencez par ajouter le premier traitement pour ce suivi.
                </p>
                <div class="mt-6">
                    <button wire:click="openTraitementModal" 
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Ajouter un traitement
                    </button>
                </div>
            </div>
        @endif
    </div>

    {{-- Modal de traitement --}}
    @if($showTraitementModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                                Gestion des Traitements
                            </h3>
                            <button wire:click="closeTraitementModal" 
                                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        
                        {{-- Intégration du composant de traitement --}}
                        <livewire:suivie.traitement-suivie :suivie-id="$suivieId" :key="'traitement-'.$suivieId" />
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
