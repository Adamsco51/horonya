<div class="min-h-screen p-6">
    {{-- En-tête avec titre et bouton d'ajout --}}
    <div class="flex justify-between items-start mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white mb-2">Gestion des Connaissements (BL)</h1>
            <p class="text-zinc-400">Suivez tous les connaissements, leurs dépenses et leur rentabilité.</p>
        </div>
        @can('create_bl')
        <flux:button 
            href="{{ route('bl.create') }}" 
            wire:navigate 
            variant="filled" 
            color="blue" 
            icon="plus"
            class="bg-blue-500 hover:bg-blue-600"
        >
            Ajouter un BL
        </flux:button>
        @endcan
    </div>

    {{-- Section de filtres --}}
    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700 p-6 mb-6">
        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Filtrer les Connaissements</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            {{-- Recherche générale --}}
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Recherche générale</label>
                <flux:input 
                    wire:model.live="search"
                    placeholder="N° BL, client, statut, créateur..."
                    icon="magnifying-glass"
                    class="bg-white dark:bg-zinc-700 border-zinc-300 dark:border-zinc-600 text-zinc-900 dark:text-white placeholder-zinc-500 dark:placeholder-zinc-400"
                />
            </div>

            {{-- Filtre par client --}}
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Par Client</label>
                <flux:select 
                    wire:model.live="clientFilter"
                    class="bg-white dark:bg-zinc-700 border-zinc-300 dark:border-zinc-600 text-zinc-900 dark:text-white"
                >
                    <option value="">Tous les Clients</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name_of_entreprise }}</option>
                    @endforeach
                </flux:select>
            </div>

            {{-- Filtre par date --}}
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Par Date de Création</label>
                <flux:input 
                    type="date"
                    wire:model.live="dateFilter"
                    placeholder="Choisir une date"
                    class="bg-white dark:bg-zinc-700 border-zinc-300 dark:border-zinc-600 text-zinc-900 dark:text-white"
                />
            </div>

            {{-- Bouton de réinitialisation --}}
            <div class="flex items-end">
                <flux:button 
                    wire:click="resetFilters"
                    variant="outline"
                    icon="arrow-path"
                    class="border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700"
                >
                    Réinitialiser
                </flux:button>
            </div>
        </div>
    </div>

    {{-- Liste des connaissements --}}
    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700 p-6">
        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Liste des Connaissements</h3>
        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-4">
            Aperçu de tous les BLs enregistrés. Affichage: {{ $bls->count() }} BL(s)
        </p>

        {{-- Tableau --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-zinc-100 dark:bg-zinc-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-zinc-700 dark:text-zinc-300 uppercase tracking-wider">
                            N° BL
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-zinc-700 dark:text-zinc-300 uppercase tracking-wider">
                            Client
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-zinc-700 dark:text-zinc-300 uppercase tracking-wider">
                            Créé par
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-zinc-700 dark:text-zinc-300 uppercase tracking-wider">
                            Date Création
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-zinc-700 dark:text-zinc-300 uppercase tracking-wider">
                            Statut BL
                        </th>
                        @can('view_financial_data')
                        <th class="px-6 py-4 text-left text-xs font-medium text-zinc-700 dark:text-zinc-300 uppercase tracking-wider">
                            Montant Alloué
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-zinc-700 dark:text-zinc-300 uppercase tracking-wider">
                            Dépenses Totales
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-zinc-700 dark:text-zinc-300 uppercase tracking-wider">
                            Solde
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-zinc-700 dark:text-zinc-300 uppercase tracking-wider">
                            Statut Financier
                        </th>
                        @endcan
                        <th class="px-6 py-4 text-left text-xs font-medium text-zinc-700 dark:text-zinc-300 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($bls as $bl)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-zinc-900 dark:text-white">
                                {{ $bl->bl_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-700 dark:text-zinc-300">
                                @if($bl->client)
                                    {{ $bl->client->name_of_entreprise }}
                                @else
                                    <span class="text-zinc-500">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-700 dark:text-zinc-300">
                                {{ $bl->creator->name ?? 'Utilisateur Inconnu' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-700 dark:text-zinc-300">
                                {{ $bl->created_at->format('d M. Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <flux:badge 
                                    color="blue" 
                                    variant="soft"
                                    icon="clock"
                                    class="bg-blue-500/20 text-blue-300"
                                >
                                    En Cours
                                </flux:badge>
                            </td>
                            @can('view_financial_data')
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-700 dark:text-zinc-300">
                                {{ number_format($bl->montant_alloue ?? 0, 0, ',', ' ') }} FCFA
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-700 dark:text-zinc-300">
                                {{ number_format($bl->depenses_totales ?? 0, 0, ',', ' ') }} FCFA
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @php
                                    $solde = ($bl->montant_alloue ?? 0) - ($bl->depenses_totales ?? 0);
                                @endphp
                                <span class="{{ $solde >= 0 ? 'text-green-400' : 'text-red-400' }}">
                                    {{ number_format($solde, 0, ',', ' ') }} FCFA
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $solde = ($bl->montant_alloue ?? 0) - ($bl->depenses_totales ?? 0);
                                @endphp
                                <flux:badge 
                                    color="{{ $solde >= 0 ? 'green' : 'red' }}" 
                                    variant="soft"
                                    class="{{ $solde >= 0 ? 'bg-green-500/20 text-green-300' : 'bg-red-500/20 text-red-300' }}"
                                >
                                    {{ $solde >= 0 ? 'Bénéfice' : 'Déficit' }}
                                </flux:badge>
                            </td>
                            @endcan
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex items-center space-x-2">
                                    <flux:button 
                                        href="{{ route('bl.show', $bl->id) }}"
                                        wire:navigate
                                        variant="ghost"
                                        size="sm"
                                        icon="eye"
                                        class="text-zinc-400 hover:text-white hover:bg-zinc-700"
                                    >
                                        Voir Détails
                                    </flux:button>
                                    <flux:icon name="chevron-right" class="w-4 h-4 text-zinc-500" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <flux:icon name="document-text" class="w-12 h-12 text-zinc-400 dark:text-zinc-500 mb-4" />
                                    <h3 class="text-lg font-medium text-zinc-700 dark:text-zinc-300 mb-2">Aucun connaissement trouvé</h3>
                                    <p class="text-zinc-500 mb-4">Commencez par créer votre premier BL.</p>
                                    @can('create_bl')
                                    <flux:button 
                                        href="{{ route('bl.create') }}" 
                                        wire:navigate 
                                        variant="filled" 
                                        color="blue"
                                        icon="plus"
                                    >
                                        Créer un BL
                                    </flux:button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($bls->hasPages())
            <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-700">
                {{ $bls->links() }}
            </div>
        @endif
    </div>
</div>
