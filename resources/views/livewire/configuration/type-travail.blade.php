<div class="container mx-auto px-4 py-6">
    <!-- Messages de feedback -->
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- En-tête -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Gestion des Types de Travail</h1>
        @if (!$editing)
            @can('create_type_travail')
            <button wire:click="resetForm" 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-plus mr-2"></i>Nouveau Type
            </button>
            @endcan
        @endif
    </div>

    <!-- Formulaire de création/édition -->
    @if (!$editing || $editingId)
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-6">
            <h2 class="text-lg font-semibold mb-4">
                {{ $editing ? 'Modifier le Type de Travail' : 'Nouveau Type de Travail' }}
            </h2>

            <form wire:submit.prevent="{{ $editing ? 'save' : 'create' }}">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Nom -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="nom">
                            Nom du Type <span class="text-red-500">*</span>
                        </label>
                        <input wire:model="nom" 
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('nom') border-red-500 @enderror" 
                               id="nom" 
                               type="text" 
                               placeholder="Ex: Transport maritime">
                        @error('nom')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Statut Actif -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Statut
                        </label>
                        <div class="flex items-center">
                            <input wire:model="actif" 
                                   type="checkbox" 
                                   id="actif" 
                                   class="mr-2 leading-tight">
                            <label for="actif" class="text-sm">
                                Type actif
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                        Description
                    </label>
                    <textarea wire:model="description" 
                              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror" 
                              id="description" 
                              rows="3" 
                              placeholder="Description du type de travail..."></textarea>
                    @error('description')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Boutons d'action -->
                <div class="flex items-center justify-between">
                    <div class="flex space-x-2">
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            <i class="fas fa-save mr-2"></i>
                            {{ $editing ? 'Modifier' : 'Créer' }}
                        </button>
                        
                        @if ($editing)
                            <button type="button" 
                                    wire:click="cancel" 
                                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                <i class="fas fa-times mr-2"></i>Annuler
                            </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    @endif

    <!-- Barre de recherche -->
    <div class="mb-4">
        <div class="flex items-center">
            <div class="relative flex-grow">
                <input wire:model.live="search" 
                       type="text" 
                       placeholder="Rechercher un type de travail..." 
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des types de travail -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th wire:click="sortBy('nom')" 
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                        <div class="flex items-center">
                            Nom
                            @if ($sortField === 'nom')
                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                            @else
                                <i class="fas fa-sort ml-1 text-gray-300"></i>
                            @endif
                        </div>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Description
                    </th>
                    <th wire:click="sortBy('actif')" 
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                        <div class="flex items-center">
                            Statut
                            @if ($sortField === 'actif')
                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                            @else
                                <i class="fas fa-sort ml-1 text-gray-300"></i>
                            @endif
                        </div>
                    </th>
                    <th wire:click="sortBy('created_at')" 
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                        <div class="flex items-center">
                            Créé le
                            @if ($sortField === 'created_at')
                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                            @else
                                <i class="fas fa-sort ml-1 text-gray-300"></i>
                            @endif
                        </div>
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($typeTravails as $typeTravail)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $typeTravail->nom }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-500">
                                {{ Str::limit($typeTravail->description, 100) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $typeTravail->actif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $typeTravail->actif ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $typeTravail->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <!-- Bouton Activer/Désactiver -->
                                @can('manage_type_travail')
                                <button wire:click="toggleStatus({{ $typeTravail->id }})" 
                                        class="text-{{ $typeTravail->actif ? 'orange' : 'green' }}-600 hover:text-{{ $typeTravail->actif ? 'orange' : 'green' }}-900"
                                        title="{{ $typeTravail->actif ? 'Désactiver' : 'Activer' }}">
                                    <i class="fas fa-{{ $typeTravail->actif ? 'pause' : 'play' }}"></i>
                                </button>
                                @endcan

                                <!-- Bouton Éditer -->
                                @can('edit_type_travail')
                                <button wire:click="edit({{ $typeTravail->id }})" 
                                        class="text-indigo-600 hover:text-indigo-900"
                                        title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @endcan

                                <!-- Bouton Supprimer -->
                                @can('delete_type_travail')
                                <button wire:click="delete({{ $typeTravail->id }})" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce type de travail ?')"
                                        class="text-red-600 hover:text-red-900"
                                        title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            @if ($search)
                                Aucun type de travail trouvé pour "{{ $search }}"
                            @else
                                Aucun type de travail enregistré
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if ($typeTravails->hasPages())
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $typeTravails->links() }}
            </div>
        @endif
    </div>
</div>
