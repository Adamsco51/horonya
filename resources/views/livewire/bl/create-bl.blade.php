<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header professionnel -->
    <div class="mb-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-gray-500 text-sm mb-6">
            <a wire:navigate href="{{ route('bl.index') }}" class="hover:text-blue-600 transition-colors">Accueil</a>
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                    clip-rule="evenodd"></path>
            </svg>
            <a wire:navigate href="{{ route('bl.index') }}" class="hover:text-blue-600 transition-colors">Bills of Lading</a>
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                    clip-rule="evenodd"></path>
            </svg>
            <span class="text-blue-600 font-medium">Nouveau BL</span>
        </nav>

        <!-- Titre et bouton retour -->
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-6">
            <div class="space-y-3">
                <div class="flex items-center">
                    <div class="bg-blue-600 p-3 rounded-xl mr-4 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl lg:text-4xl font-bold text-gray-900">Créer un nouveau BL</h1>
                        <div class="flex items-center mt-2 text-sm text-gray-500">
                            <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                            Système actif
                        </div>
                    </div>
                </div>
                <p class="text-gray-600 text-lg max-w-2xl">
                    Créez un nouveau Bill of Lading en remplissant les informations requises ci-dessous.
                    Tous les champs marqués d'un astérisque (*) sont obligatoires.
                </p>
            </div>

            <button onclick="goBack()"
                class="inline-flex items-center px-6 py-3 bg-white border-2 border-gray-300 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour à la liste
            </button>
        </div>
    </div>

    <!-- Formulaire principal -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
        <!-- En-tête du formulaire -->
        <div class="bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="bg-white bg-opacity-20 p-3 rounded-lg mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">Informations du Bill of Lading</h2>
                        <p class="text-blue-100 mt-1">Formulaire de création - Version 2.1</p>
                    </div>
                </div>
                <div class="hidden sm:flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                    <span class="text-white text-sm font-medium">Prêt à sauvegarder</span>
                </div>
            </div>
        </div>

        <form wire:submit="save" class="p-8 space-y-8">
            <!-- Section 1: Informations principales -->
            <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl p-6 border border-gray-200">
                <div class="flex items-center mb-6">
                    <div class="bg-blue-100 p-2 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900">Informations principales</h3>
                    <div class="ml-auto">
                        <span
                            class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Obligatoire</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Numéro BL -->
                    <div class="space-y-2">
                        <label for="bl_number" class="flex items-center text-sm font-semibold text-gray-700">
                            <span class="text-red-500 mr-1">*</span>
                            Numéro BL
                            <svg class="w-4 h-4 ml-1 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </label>
                        <input type="text" id="bl_number" wire:model="bl_number" placeholder="ex: MEDU245115"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-200 focus:ring-4 focus:outline-none transition-all duration-200 font-medium"
                            required />
                        @error('bl_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500">Format recommandé: 4 lettres + 6 chiffres</p>
                    </div>

                    <!-- Client associé -->
                    <div class="space-y-2">
                        <label for="client_id" class="flex items-center text-sm font-semibold text-gray-700">
                            <span class="text-red-500 mr-1">*</span>
                            Client associé
                        </label>
                        <select id="client_id" wire:model="client_id"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-200 focus:ring-4 focus:outline-none transition-all duration-200 font-medium">
                            <option value="">Sélectionnez un client</option>
                            @foreach($bl_clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name_of_entreprise }}</option>
                            @endforeach
                        </select>
                        @error('client_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Détails du transport -->
            <div class="bg-gradient-to-r from-gray-50 to-green-50 rounded-xl p-6 border border-gray-200">
                <div class="flex items-center mb-6">
                    <div class="bg-green-100 p-2 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900">Détails du transport</h3>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Type de travail -->
                    <div class="space-y-2">
                        <label for="type_travail_id" class="flex items-center text-sm font-semibold text-gray-700">
                            <span class="text-red-500 mr-1">*</span>
                            Type de travail
                        </label>
                        <select id="type_travail_id" wire:model="type_travail_id"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-200 focus:ring-4 focus:outline-none transition-all duration-200 font-medium bg-white"
                            required>
                            <option value="">-- Sélectionner --</option>
                            @foreach($bl_types as $typeTravail)
                                <option value="{{ $typeTravail->id }}">{{ $typeTravail->type_work }}</option>
                            @endforeach
                        </select>
                        @error('type_travail_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Nombre de conteneurs -->
                    <div class="space-y-2">
                        <label for="nbr_conteneur" class="flex items-center text-sm font-semibold text-gray-700">
                            <span class="text-red-500 mr-1">*</span>
                            Nombre de conteneurs
                        </label>
                        <input type="number" id="nbr_conteneur" wire:model="nbr_conteneur" min="1" max="999" placeholder="1"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-200 focus:ring-4 focus:outline-none transition-all duration-200 font-medium"
                            required />
                        @error('nbr_conteneur') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Type de conteneur -->
                    <div class="space-y-2">
                        <label for="type_of_conteneur" class="text-sm font-semibold text-gray-700">
                            Type de conteneur
                        </label>
                        <input type="text" id="type_of_conteneur" wire:model="type_of_conteneur" placeholder="ex: 40HC, 20DV"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-200 focus:ring-4 focus:outline-none transition-all duration-200 font-medium" />
                        @error('type_of_conteneur') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                    <!-- Navire -->
                    <div class="space-y-2">
                        <label for="ship_name" class="text-sm font-semibold text-gray-700">
                            Navire / Moyen de transport
                        </label>
                        <input type="text" id="ship_name" wire:model="ship_name" placeholder="ex: MSC OSCAR"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-200 focus:ring-4 focus:outline-none transition-all duration-200 font-medium" />
                        @error('ship_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Catégorie -->
                    <div class="space-y-2">
                        <label for="category" class="text-sm font-semibold text-gray-700">
                            Catégorie
                        </label>
                        <input type="text" id="category" wire:model="category" placeholder="ex: Marchandises générales"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-200 focus:ring-4 focus:outline-none transition-all duration-200 font-medium" />
                        @error('category') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Section 3: Notes et commentaires -->
            <div class="bg-gradient-to-r from-gray-50 to-yellow-50 rounded-xl p-6 border border-gray-200">
                <div class="flex items-center mb-6">
                    <div class="bg-yellow-100 p-2 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900">Notes et commentaires</h3>
                    <div class="ml-auto">
                        <span
                            class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Optionnel</span>
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="note" class="text-sm font-semibold text-gray-700">
                        Observations
                    </label>
                    <textarea id="note" wire:model="note" rows="4"
                        placeholder="Ajoutez ici toute information complémentaire concernant ce BL..."
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-200 focus:ring-4 focus:outline-none transition-all duration-200 resize-none"></textarea>
                    @error('note') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    <p class="text-xs text-gray-500">Maximum 500 caractères</p>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                <button type="submit" wire:loading.attr="disabled"
                    class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-4 px-8 rounded-xl transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-200 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                    <div class="flex items-center justify-center">
                        <svg wire:loading wire:target="save" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <svg wire:loading.remove wire:target="save" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        <span wire:loading.remove wire:target="save">Enregistrer le BL</span>
                        <span wire:loading wire:target="save">Enregistrement...</span>
                    </div>
                </button>

                <button type="button" wire:click="resetForm"
                    class="sm:w-auto bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-4 px-8 rounded-xl transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-gray-200 border-2 border-gray-300">
                    <div class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        Réinitialiser
                    </div>
                </button>
            </div>
        </form>
    </div
