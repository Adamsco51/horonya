<div class="max-w-4xl mx-auto space-y-6 p-4">
    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <!-- Messages de feedback -->
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
    
    <!-- En-tête -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Nouveau Suivie</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">Créer un nouveau suivi de navire</p>
        </div>
        <a href="{{ route('suivies.index') }}" wire:navigate class="flex items-center space-x-2 px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Retour à la liste</span>
        </a>
    </div>

    <!-- Formulaire -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg dark:shadow-gray-900/20 border border-gray-200 dark:border-gray-700">
        <form wire:submit.prevent="save">
            <div class="p-6 space-y-8">
                <!-- Section 1: Informations Client -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Informations Client
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Client -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Client *</label>
                            <select wire:model.live="client_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">Sélectionnez un client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name_of_entreprise }}</option>
                                @endforeach
                            </select>
                            @error('client_id')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Numéro BL -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Numéro BL *</label>
                            <input 
                                type="text" 
                                wire:model.live="numero_bl"
                                placeholder="Ex: BL2024001"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                            />
                            @error('numero_bl')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @else
                                <p class="mt-1 text-xs text-gray-600 dark:text-gray-400">
                                    Saisissez le numéro BL - Le client sera automatiquement sélectionné s'il existe
                                </p>
                            @enderror
                        </div>

                        <!-- Bill of Lading -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Bill of Lading (Optionnel)
                                @if($client_id && count($availableBLs) > 0)
                                    <span class="text-xs text-green-600 dark:text-green-400 ml-2">
                                        {{ count($availableBLs) }} BL(s) disponible(s)
                                    </span>
                                @endif
                            </label>
                            <select wire:model.live="bils_ofladings_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">
                                    @if($client_id)
                                        Sélectionnez un BL existant ou laissez vide pour créer un nouveau
                                    @else
                                        Sélectionnez d'abord un client
                                    @endif
                                </option>
                                @foreach($availableBLs as $bl)
                                    <option value="{{ $bl->id }}">
                                        {{ $bl->bl_number }} - {{ $bl->ship_name }}
                                        @if($bl->type_of_conteneur)
                                            ({{ $bl->type_of_conteneur }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('bils_ofladings_id')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @else
                                <p class="mt-1 text-xs text-gray-600 dark:text-gray-400">
                                    @if($client_id && count($availableBLs) > 0)
                                        Choisissez un BL existant ou laissez vide pour en créer un nouveau
                                    @elseif($client_id)
                                        Aucun BL existant pour ce client - Un nouveau sera créé
                                    @else
                                        Sélectionnez un client pour voir les BL disponibles
                                    @endif
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: Détails du Suivi -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Détails du Suivi
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Agent Responsable -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Agent Responsable *</label>
                            <select wire:model="agent_transit_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">Sélectionnez un agent</option>
                                @foreach($agentsTransit as $agent)
                                    <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                @endforeach
                            </select>
                            @error('agent_transit_id')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @else
                                <p class="mt-1 text-xs text-gray-600 dark:text-gray-400">Agent responsable du suivi</p>
                            @enderror
                        </div>

                        <!-- Date d'Arrivée Prévue (ETA) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date d'Arrivée Prévue (ETA) *</label>
                            <input 
                                type="datetime-local" 
                                wire:model.live="ETA"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            />
                            @error('ETA')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @else
                                <p class="mt-1 text-xs text-gray-600 dark:text-gray-400">Date et heure d'arrivée prévue</p>
                            @enderror
                        </div>

                        <!-- Statut Initial -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Statut Initial</label>
                            <select wire:model="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="stockage">Stockage</option>
                                <option value="traitement">Traitement</option>
                                <option value="depasse">Dépassé</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @else
                                <p class="mt-1 text-xs text-gray-600 dark:text-gray-400">Statut calculé automatiquement selon l'ETA</p>
                            @enderror
                        </div>

                        <!-- Jours Restants et État Calculé -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jours Restants / État</label>
                            <div class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white">
                                @if($ETA && $joursRestants !== null)
                                    <div class="flex items-center justify-between">
                                        <!-- Affichage des jours restants -->
                                        <div class="flex items-center">
                                            @if($joursRestants > 0)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                    {{ $joursRestants }} jour{{ $joursRestants > 1 ? 's' : '' }} restant{{ $joursRestants > 1 ? 's' : '' }}
                                                </span>
                                            @elseif($joursRestants === 0)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    Aujourd'hui
                                                </span>
                                            @elseif($joursRestants >= -10)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                                                    {{ abs($joursRestants) }} jour{{ abs($joursRestants) > 1 ? 's' : '' }} de retard
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                    {{ abs($joursRestants) }} jours de retard (DÉPASSÉ)
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <!-- Statut calculé -->
                                        <div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($status === 'stockage') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                                @elseif($status === 'traitement') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                @endif">
                                                {{ ucfirst($status) }}
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">Sélectionnez une ETA pour voir le calcul</span>
                                @endif
                            </div>
                            <p class="mt-1 text-xs text-gray-600 dark:text-gray-400">
                                Calcul automatique : Stockage (>7j), Traitement (≤7j), Dépassé (>10j de retard)
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Statut Automatique -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Statut Automatique
                    </h3>
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Statut (Calculé automatiquement)</label>
                        <div class="mt-2" id="status-display">
                            <div class="flex items-center text-gray-500 dark:text-gray-400">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Sera déterminé automatiquement après sélection de l'ETA</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations détaillées (masquées par défaut) -->
                <div id="detailed-info" class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg p-4 hidden">
                    <h4 class="text-sm font-medium text-blue-900 dark:text-blue-200 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Informations calculées
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div class="flex flex-col">
                            <span class="text-blue-700 dark:text-blue-300 font-medium">Jours restants</span>
                            <span id="days-remaining" class="text-gray-900 dark:text-white mt-1 font-semibold">-</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-blue-700 dark:text-blue-300 font-medium">Alerte nécessaire</span>
                            <span id="alert-needed" class="text-gray-900 dark:text-white mt-1 font-semibold">-</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-blue-700 dark:text-blue-300 font-medium">Statut assigné</span>
                            <span id="assigned-status" class="text-gray-900 dark:text-white mt-1 font-semibold">-</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between rounded-b-lg">
                <button type="button" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors duration-200">
                    Réinitialiser
                </button>

                <div class="flex items-center space-x-3">
                    <button type="button" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors duration-200">
                        Annuler
                    </button>
                    <button type="submit" class="flex items-center space-x-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Créer le suivie</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Aide -->
    <div class="bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
        <div class="flex">
            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Informations importantes</h3>
                <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Le statut est automatiquement déterminé selon l'ETA : <strong>Traitement</strong> si ≤ 7 jours, <strong>Stockage</strong> sinon</li>
                        <li>Une alerte sera générée quotidiennement si l'ETA est dans 7 jours ou moins</li>
                        <li>L'état peut être modifié manuellement selon l'évolution du navire</li>
                        <li>Un agent de transit peut être assigné pour les dossiers en traitement</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>