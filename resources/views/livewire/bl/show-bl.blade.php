<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Toast Container --}}
    

    {{-- Header avec titre et boutons d'action --}}
    <div class="mb-8">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">BL N°: {{ $bl->bl_number }}</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Détails du connaissement pour {{ $bl->client->name_of_entreprise ?? 'Client non défini' }}</p>
            </div>
            <div class="flex space-x-3">
                <flux:button 
                    :href="route('bl.index')" 
                    variant="outline"
                    icon="arrow-left"
                    wire:navigate
                >
                    Retour
                </flux:button>
                <flux:button 
                    :href="route('bl.edit', $bl->id)" 
                    variant="primary"
                    icon="pencil"
                    wire:navigate
                >
                    Modifier BL
                </flux:button>
                <flux:button 
                    wire:click="deleteBl" 
                    wire:confirm="Êtes-vous sûr de vouloir supprimer ce BL?"
                    variant="danger"
                    icon="trash"
                >
                    Supprimer BL
                </flux:button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Section principale - Informations du BL --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex justify-between items-start mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Informations du BL</h2>
                    <div class="flex space-x-2">
                        @if($bl->status === 'en_cours')
                            <flux:badge color="blue" icon="clock">En Cours</flux:badge>
                        @else
                            <flux:badge color="green" icon="check">Rédigé</flux:badge>
                        @endif
                    </div>
                </div>

                <div class="space-y-6">
                    {{-- Informations de base --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">N° BL</label>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $bl->bl_number }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Date de création</label>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $bl->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    {{-- Montant et détails financiers --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @can('view_financial_data')
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Montant Alloué</label>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($bl->montant_alloue ?? 0, 0, ',', ' ') }} FCFA</p>
                        </div>
                        @endcan
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Créé par</label>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $bl->creator->name ?? 'Utilisateur inconnu' }}</p>
                        </div>
                    </div>

                    @can('view_financial_data')
                    {{-- Dépenses et solde --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Dépenses Totales</label>
                            <p class="text-lg font-semibold text-red-600 dark:text-red-400">{{ number_format($bl->depenses_totales ?? 0, 0, ',', ' ') }} FCFA</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Solde Actuel</label>
                            <p class="text-lg font-semibold text-green-600 dark:text-green-400">{{ number_format(($bl->montant_alloue ?? 0) - ($bl->depenses_totales ?? 0), 0, ',', ' ') }} FCFA</p>
                        </div>
                    </div>
                    @endcan

                    {{-- Type de travail --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Type de Travail</label>
                        <p class="text-sm text-gray-900 dark:text-white font-medium">{{ $bl->type_travail->type_work ?? 'TRANSIT' }}</p>
                    </div>

                    {{-- Catégories manuelles --}}
                    @if($bl->categories_manuelles)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Catégories Manuelles</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach(explode(',', $bl->categories_manuelles) as $categorie)
                                <flux:badge color="gray">{{ trim($categorie) }}</flux:badge>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Section Conteneurs Associés --}}
            <div class="mt-8 bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                        <flux:icon.cube class="w-5 h-5 mr-2" />
                        Conteneurs Associés
                    </h2>
                    <flux:button 
                        wire:click="addContainer" 
                        variant="primary"
                        icon="plus"
                        size="sm"
                    >
                        Ajouter Conteneur
                    </flux:button>
                </div>

                @if($bl->conteneurs && $bl->conteneurs->count() > 0)
                    <div class="space-y-4">
                        @foreach($bl->conteneurs as $conteneur)
                            <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-white">{{ $conteneur->numero }}</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $conteneur->type ?? 'Type non défini' }}</p>
                                    </div>
                                    <flux:button 
                                        wire:click="removeContainer({{ $conteneur->id }})" 
                                        variant="ghost"
                                        icon="trash"
                                        size="sm"
                                    />
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <flux:icon.cube class="w-12 h-12 mx-auto text-gray-400 mb-4" />
                        <p class="text-gray-500 dark:text-gray-400">Aucun conteneur associé à ce BL pour le moment.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Sidebar droite --}}
        <div class="space-y-8">
            {{-- Section Infos Client --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center mb-4">
                    <flux:icon.user class="w-5 h-5 mr-2" />
                    Infos Client
                </h3>

                @if($bl->client)
                    <div class="space-y-4">
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white">{{ $bl->client->name_of_entreprise }}</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $bl->client->name_contact }}</p>
                        </div>

                        @if($bl->client->email)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Email</label>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $bl->client->email }}</p>
                        </div>
                        @endif

                        @if($bl->client->phone)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Téléphone</label>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $bl->client->phone }}</p>
                        </div>
                        @endif

                        @if($bl->client->adresse)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Adresse</label>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $bl->client->adresse }}</p>
                        </div>
                        @endif

                        <div class="pt-4 border-t border-gray-200 dark:border-gray-600">
                            <flux:button 
                                :href="route('clients.show', $bl->client->id)" 
                                variant="outline"
                                size="sm"
                                icon="arrow-right"
                                wire:navigate
                                class="w-full"
                            >
                                Voir fiche client
                            </flux:button>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Aucun client associé</p>
                @endif
            </div>

            {{-- Section Actions Rapides --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center mb-4">
                    <flux:icon.bolt class="w-5 h-5 mr-2" />
                    Actions Rapides
                </h3>

                <div class="space-y-3">
                    <flux:button 
                        wire:click="generateReport" 
                        variant="outline"
                        icon="document-text"
                        class="w-full justify-start"
                    >
                        Générer un rapport (PDF)
                    </flux:button>

                    <flux:button 
                        wire:click="exportAccounting" 
                        variant="outline"
                        icon="calculator"
                        class="w-full justify-start"
                    >
                        Exporter pour comptabilité
                    </flux:button>

                    <flux:button 
                        wire:click="archiveBl" 
                        variant="outline"
                        icon="archive-box"
                        class="w-full justify-start"
                    >
                        Archiver le BL
                    </flux:button>
                </div>
            </div>
        </div>
    </div>
</div>
