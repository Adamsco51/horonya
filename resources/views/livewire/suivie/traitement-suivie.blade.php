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

    {{-- Informations du suivi --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                Traitement du Suivi
            </h2>
            <div class="flex items-center space-x-4">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Progression: {{ $suivie->pourcentage_progression }}%
                </div>
                <div class="w-32 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $suivie->pourcentage_progression }}%"></div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
                <span class="font-medium text-gray-700 dark:text-gray-300">Client:</span>
                <span class="text-gray-900 dark:text-white">{{ $suivie->client->name_of_entreprise ?? 'Non défini' }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-700 dark:text-gray-300">BL:</span>
                <span class="text-gray-900 dark:text-white">{{ $suivie->bilsOflading->bl_number ?? 'Non défini' }}</span>
            </div>
            <div>
                <span class="font-medium text-gray-700 dark:text-gray-300">Agent:</span>
                <span class="text-gray-900 dark:text-white">{{ $suivie->agentTransit->name ?? 'Non assigné' }}</span>
            </div>
        </div>
    </div>

    {{-- Formulaire d'ajout d'étape --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
            Ajouter une Étape de Traitement
        </h3>

        <form wire:submit.prevent="ajouterEtape" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Sélection de l'étape --}}
                <div>
                    <label for="selectedEtapeId" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Étape de Traitement *
                    </label>
                    <select wire:model="selectedEtapeId" id="selectedEtapeId" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Sélectionner une étape</option>
                        @foreach($etapes as $etape)
                            <option value="{{ $etape->id }}">{{ $etape->nom }}</option>
                        @endforeach
                    </select>
                    @error('selectedEtapeId')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Statut --}}
                <div>
                    <label for="statut" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Statut *
                    </label>
                    <select wire:model="statut" id="statut" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="en_cours">En cours</option>
                        <option value="termine">Terminé</option>
                        <option value="bloque">Bloqué</option>
                    </select>
                    @error('statut')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Commentaire --}}
            <div>
                <label for="commentaire" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Commentaire
                </label>
                <textarea wire:model="commentaire" id="commentaire" rows="3" 
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                          placeholder="Commentaire sur cette étape..."></textarea>
                @error('commentaire')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Documents --}}
            <div>
                <label for="documents" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Documents joints
                </label>
                <input type="file" wire:model="documents" id="documents" multiple 
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Formats acceptés: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG. Taille max: 10MB par fichier.
                </p>
                @error('documents.*')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Boutons d'action --}}
            <div class="flex justify-end space-x-3">
                <button type="button" wire:click="resetForm" 
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Annuler
                </button>
                <button type="submit" 
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Ajouter l'étape
                </button>
            </div>
        </form>
    </div>

    {{-- Liste des étapes de traitement --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
            Historique des Traitements
        </h3>

        @if($suiviTraitements->count() > 0)
            <div class="space-y-4">
                @foreach($suiviTraitements as $traitement)
                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h4 class="text-lg font-medium text-gray-900 dark:text-white">
                                        {{ $traitement->etapeTraitement->nom }}
                                    </h4>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($traitement->statut === 'termine') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif($traitement->statut === 'bloque') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                        @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $traitement->statut)) }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600 dark:text-gray-400 mb-3">
                                    <div>
                                        <span class="font-medium">Réalisé par:</span> {{ $traitement->user->name }}
                                    </div>
                                    <div>
                                        <span class="font-medium">Date:</span> {{ $traitement->date_realisation->format('d/m/Y H:i') }}
                                    </div>
                                </div>

                                @if($traitement->commentaire)
                                    <div class="mb-3">
                                        <span class="font-medium text-gray-700 dark:text-gray-300">Commentaire:</span>
                                        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $traitement->commentaire }}</p>
                                    </div>
                                @endif

                                @if($traitement->documents && count($traitement->documents) > 0)
                                    <div class="mb-3">
                                        <span class="font-medium text-gray-700 dark:text-gray-300">Documents joints:</span>
                                        <div class="flex flex-wrap gap-2 mt-2">
                                            @foreach($traitement->documents as $index => $document)
                                                <button wire:click="downloadDocument({{ $traitement->id }}, {{ $index }})"
                                                        class="inline-flex items-center px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-md text-xs font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    {{ $document['name'] }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center space-x-2 ml-4">
                                {{-- Boutons de changement de statut --}}
                                @if($traitement->statut !== 'termine')
                                    <button wire:click="updateStatut({{ $traitement->id }}, 'termine')"
                                            class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300"
                                            title="Marquer comme terminé">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>
                                @endif

                                @if($traitement->statut !== 'bloque')
                                    <button wire:click="updateStatut({{ $traitement->id }}, 'bloque')"
                                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                                            title="Marquer comme bloqué">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                        </svg>
                                    </button>
                                @endif

                                {{-- Bouton de suppression --}}
                                <button wire:click="supprimerEtape({{ $traitement->id }})"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette étape ?')"
                                        class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                                        title="Supprimer l'étape">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Aucune étape de traitement</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Commencez par ajouter une étape de traitement.</p>
            </div>
        @endif
    </div>
</div>
