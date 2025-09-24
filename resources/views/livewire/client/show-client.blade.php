<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Toast Container --}}
    
    {{-- Header avec titre et boutons d'action --}}
    <div class="mb-8">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $client->name_of_entreprise }}</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Détails et historique pour {{ $client->name_contact }}</p>
            </div>
            <div class="flex space-x-3">
                <flux:button 
                    :href="route('clients.index')" 
                    variant="outline"
                    icon="arrow-left"
                    wire:navigate
                >
                    Retour
                </flux:button>
                <flux:button 
                    :href="route('clients.edit', $client->id)" 
                    variant="primary"
                    icon="pencil"
                    wire:navigate
                >
                    Modifier
                </flux:button>
                <flux:button 
                    wire:click="deleteClient" 
                    wire:confirm="Êtes-vous sûr de vouloir supprimer ce client?"
                    variant="danger"
                    icon="trash"
                >
                    Supprimer
                </flux:button>
            </div>
        </div>
    </div>

    {{-- Grille principale avec informations du client et actions --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Section principale - Informations du client --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Carte des informations de base --}}
            <div class="p-6 bg-white dark:bg-gray-800">
                <flux:heading size="lg" class="mb-6">Informations du Client</flux:heading>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Informations de contact --}}
                    <div class="space-y-4">
                        <flux:subheading>Contact</flux:subheading>
                        
                        <div class="space-y-3">
                            <div class="flex items-center space-x-3">
                                <flux:icon.user class="w-5 h-5 text-gray-400" />
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Nom du contact</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $client->name_contact }}</p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3">
                                <flux:icon.envelope class="w-5 h-5 text-gray-400" />
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Email</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $client->email }}</p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3">
                                <flux:icon.phone class="w-5 h-5 text-gray-400" />
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Téléphone</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $client->phone }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Informations de l'entreprise --}}
                    <div class="space-y-4">
                        <flux:subheading>Entreprise</flux:subheading>
                        
                        <div class="space-y-3">
                            <div class="flex items-center space-x-3">
                                <flux:icon.building-office class="w-5 h-5 text-gray-400" />
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Nom de l'entreprise</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $client->name_of_entreprise }}</p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3">
                                <flux:icon.map-pin class="w-5 h-5 text-gray-400" />
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Adresse</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $client->adresse }}</p>
                                </div>
                            </div>
                
                            @if($client->country)
                            <div class="flex items-center space-x-3">
                                <flux:icon.globe-alt class="w-5 h-5 text-gray-400" />
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Pays</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $client->country }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Carte des BLs associés --}}
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <flux:heading size="lg">Bons de Livraison</flux:heading>
                    <flux:button 
                        :href="route('bl.create', ['client_id' => $client->id])" 
                        variant="primary"
                        icon="plus"
                        size="sm"
                        wire:navigate
                    >
                        Nouveau BL
                    </flux:button>
                </div>

                @if($client->bls && $client->bls->count() > 0)
                    <div class="space-y-3">
                        @foreach($client->bls->take(5) as $bl)
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <flux:badge 
                                            color="{{ $bl->status === 'delivered' ? 'green' : ($bl->status === 'pending' ? 'yellow' : 'blue') }}"
                                            size="sm"
                                        >
                                            {{ ucfirst($bl->status ?? 'En cours') }}
                                        </flux:badge>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">BL #{{ $bl->numero_bl }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $bl->created_at->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                                <flux:button 
                                    :href="route('bl.show', $bl->id)" 
                                    variant="ghost"
                                    icon="eye"
                                    size="sm"
                                    wire:navigate
                                >
                                    Voir
                                </flux:button>
                            </div>
                        @endforeach
                        
                        @if($client->bls->count() > 5)
                            <div class="text-center pt-4">
                                <flux:button 
                                    :href="route('bl.index', ['client' => $client->id])" 
                                    variant="ghost"
                                    wire:navigate
                                >
                                    Voir tous les BLs ({{ $client->bls->count() }})
                                </flux:button>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-8">
                        <flux:icon.document-text class="w-12 h-12 text-gray-400 mx-auto mb-4" />
                        <p class="text-gray-500 dark:text-gray-400">Aucun bon de livraison pour ce client</p>
                        <flux:button 
                            :href="route('bl.create', ['client_id' => $client->id])" 
                            variant="primary"
                            class="mt-4"
                            wire:navigate
                        >
                            Créer le premier BL
                        </flux:button>
                    </div>
                @endif
            </div>
        </div>

        {{-- Sidebar avec actions rapides --}}
        <div class="space-y-6">
            
            {{-- Carte des statistiques --}}
            <div class="p-6">
                <flux:heading size="lg" class="mb-6">Statistiques</flux:heading>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Total BLs</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $client->bls ? $client->bls->count() : 0 }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500 dark:text-gray-400">BLs Livrés</span>
                        <span class="font-semibold text-green-600">{{ $client->bls ? $client->bls->where('status', 'delivered')->count() : 0 }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500 dark:text-gray-400">BLs En Cours</span>
                        <span class="font-semibold text-yellow-600">{{ $client->bls ? $client->bls->where('status', 'pending')->count() : 0 }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Client depuis</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $client->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
