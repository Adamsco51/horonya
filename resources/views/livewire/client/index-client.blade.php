<div>
    <div class="space-y-6">
        {{-- En-tête de la page --}}
        <div class="flex justify-between items-center">
            <div>
                <flux:heading size="xl">Gestion des Clients</flux:heading>
                <flux:subheading>Gérez vos clients et leurs informations de contact</flux:subheading>
            </div>
            @can('create_clients')
                <flux:button 
                    href="{{ route('clients.create') }}"
                    icon="plus">
                    Nouveau Client
                </flux:button>
            @endcan
        </div>

        {{-- Barre de recherche --}}
        <flux:card>
            <div class="flex items-center space-x-4">
                <div class="flex-1">
                    <flux:input 
                        wire:model.live.debounce.300ms="search"
                        placeholder="Rechercher par nom d'entreprise, contact, email ou téléphone..."
                        icon="magnifying-glass" />
                </div>
            </div>
        </flux:card>

        {{-- Tableau des clients --}}
        <flux:card>
            <div class="overflow-x-auto">
                <flux:table>
                    <flux:columns>
                        <flux:column>Entreprise</flux:column>
                        <flux:column>Contact</flux:column>
                        <flux:column>Email</flux:column>
                        <flux:column>Téléphone</flux:column>
                        <flux:column>Pays</flux:column>
                        <flux:column>Créé par</flux:column>
                        <flux:column>Actions</flux:column>
                    </flux:columns>

                    <flux:rows>
                        @forelse ($clients as $client)
                            <flux:row>
                                <flux:cell>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $client->name_of_entreprise }}
                                    </div>
                                </flux:cell>
                                
                                <flux:cell>
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $client->name_contact }}
                                    </div>
                                </flux:cell>
                                
                                <flux:cell>
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $client->email ?: '-' }}
                                    </div>
                                </flux:cell>
                                
                                <flux:cell>
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $client->phone }}
                                    </div>
                                </flux:cell>
                                
                                <flux:cell>
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $client->country ?: '-' }}
                                    </div>
                                </flux:cell>
                                
                                <flux:cell>
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $client->creator->name ?? 'Utilisateur inconnu' }}
                                    </div>
                                </flux:cell>
                                
                                <flux:cell>
                                    <div class="flex justify-end space-x-2">
                                        <flux:button 
                                            href="{{ route('clients.show', $client->id) }}"
                                            variant="ghost" 
                                            size="sm"
                                            icon="eye">
                                            Voir détails
                                        </flux:button>
                                    </div>
                                </flux:cell>
                            </flux:row>
                        @empty
                            <flux:row>
                                <flux:cell colspan="7">
                                    <div class="text-center py-12">
                                        <flux:icon.user-group class="w-12 h-12 mx-auto mb-4 text-gray-300" />
                                        <flux:heading size="lg">Aucun client trouvé</flux:heading>
                                        <flux:subheading>
                                            @if($search)
                                                Aucun client ne correspond à votre recherche.
                                            @else
                                                Commencez par créer votre premier client.
                                            @endif
                                        </flux:subheading>
                                        @can('create_clients')
                                            @if(!$search)
                                                <div class="mt-4">
                                                    <flux:button 
                                                        href="{{ route('clients.create') }}"
                                                        icon="plus">
                                                        Créer un client
                                                    </flux:button>
                                                </div>
                                            @endif
                                        @endcan
                                    </div>
                                </flux:cell>
                            </flux:row>
                        @endforelse
                    </flux:rows>
                </flux:table>
            </div>

            {{-- Pagination --}}
            @if($clients->hasPages())
                <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700">
                    {{ $clients->links() }}
                </div>
            @endif
        </flux:card>
    </div>
</div>
