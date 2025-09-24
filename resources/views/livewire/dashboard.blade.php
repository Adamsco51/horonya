<div class="space-y-6">
    {{-- Cartes de statistiques principales --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        {{-- Total BL --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total BL</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $statistics['total_bls'] }}</p>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-green-600 dark:text-green-400">+{{ $statistics['bls_this_month'] }}</span>
                <span class="text-sm text-gray-600 dark:text-gray-400 ml-1">ce mois</span>
            </div>
        </div>

        {{-- Total Clients --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Clients</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $statistics['total_clients'] }}</p>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-green-600 dark:text-green-400">+{{ $statistics['new_clients_this_month'] }}</span>
                <span class="text-sm text-gray-600 dark:text-gray-400 ml-1">nouveaux ce mois</span>
            </div>
        </div>

        {{-- Total Suivies --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Suivies</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $statistics['total_suivies'] }}</p>
                </div>
                <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-full">
                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-gray-600 dark:text-gray-400">Navires suivis</span>
            </div>
        </div>

        {{-- Suivies avec Alerte --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Alertes</p>
                    <p class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $statistics['suivies_avec_alerte'] }}</p>
                </div>
                <div class="p-3 bg-red-100 dark:bg-red-900 rounded-full">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-red-600 dark:text-red-400">≤ 7 jours</span>
            </div>
        </div>

        {{-- Types de Travail --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Types de Travail</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $statistics['total_types_travail'] }}</p>
                </div>
                <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Utilisateurs --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Agents Transit</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $statistics['total_users'] }}</p>
                </div>
                <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-full">
                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Section des BL récents et répartition par type --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- BL récents --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">BL Récents</h3>
            <div class="space-y-3">
                @forelse($statistics['recent_bls'] as $bl)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $bl->bl_number }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $bl->client->name_of_entreprise ?? 'Client non défini' }} - 
                                {{ $bl->type_travail->type_work ?? 'Type non défini' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $bl->created_at->format('d/m/Y') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-500">{{ $bl->creator->name ?? 'Utilisateur inconnu' }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">Aucun BL trouvé</p>
                @endforelse
            </div>
        </div>

        {{-- Répartition par type de travail --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Répartition par Type de Travail</h3>
            <div class="space-y-3">
                @forelse($statistics['bls_by_type'] as $type => $count)
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $type ?? 'Non défini' }}</span>
                        <div class="flex items-center space-x-2">
                            <div class="w-20 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $statistics['total_bls'] > 0 ? ($count / $statistics['total_bls']) * 100 : 0 }}%"></div>
                            </div>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $count }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">Aucune donnée disponible</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Section des métriques du workflow --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            Métriques du Workflow
        </h3>
        @livewire('dashboard.workflow-metrics')
    </div>

    {{-- Section des suivis avec alertes --}}
    @if(count($statistics['suivies_avec_alerte_details']) > 0)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-red-600 dark:text-red-400">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                Suivis avec Alertes ({{ count($statistics['suivies_avec_alerte_details']) }})
            </h3>
            <span class="text-sm text-gray-600 dark:text-gray-400">ETA ≤ 7 jours</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Client
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            BL
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            ETA
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Jours Restants
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Agent
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($statistics['suivies_avec_alerte_details'] as $suivie)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $suivie->client->name_of_entreprise ?? 'Client non défini' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">
                                {{ $suivie->bilsOflading->bl_number ?? 'BL non défini' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">
                                {{ $suivie->ETA ? $suivie->ETA->format('d/m/Y') : 'Non défini' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($suivie->joursRestants < 0) bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                @elseif($suivie->joursRestants <= 3) bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                @endif">
                                @if($suivie->joursRestants < 0)
                                    {{ abs($suivie->joursRestants) }} jour(s) de retard
                                @else
                                    {{ $suivie->joursRestants }} jour(s)
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">
                                {{ $suivie->agentTransit->name ?? 'Non assigné' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($suivie->status === 'traitement') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                @endif">
                                {{ ucfirst($suivie->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('suivies.show', $suivie->id) }}" 
                               class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                Voir détails
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
