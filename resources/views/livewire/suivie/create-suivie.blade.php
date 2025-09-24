<div>
    {{-- Toast Container --}}
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <div class="max-w-4xl mx-auto space-y-6">
        {{-- En-tête --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Nouveau Suivie</h1>
                <p class="text-sm text-gray-600">Créer un nouveau suivi de navire</p>
            </div>
            <flux:button href="{{ route('suivies.index') }}" variant="ghost">
                <flux:icon.arrow-left class="size-4" />
                Retour à la liste
            </flux:button>
        </div>

        {{-- Formulaire --}}
        <div class="bg-white rounded-lg shadow">
            <form wire:submit="save">
                <div class="p-6 space-y-8">
                    {{-- Section 1: Informations Client --}}
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <flux:icon.user class="size-5 mr-2 text-blue-600" />
                            Informations Client
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Client --}}
                            <div>
                                <flux:field>
                                    <flux:label>Client *</flux:label>
                                    <flux:select wire:model.live="client_id" placeholder="Sélectionnez un client" required>
                                        @foreach($clients as $client)
                                            <flux:select.option value="{{ $client->id }}">{{ $client->name_of_entreprise }}</flux:select.option>
                                        @endforeach
                                    </flux:select>
                                    <flux:error name="client_id" />
                                </flux:field>
                            </div>

                            {{-- Bill of Lading --}}
                            <div>
                                <flux:field>
                                    <flux:label>Bill of Lading (Optionnel)</flux:label>
                                    <flux:select wire:model="bils_ofladings_id" placeholder="Sélectionnez un BL existant" {{ empty($availableBLs) ? 'disabled' : '' }}>
                                        @if(!empty($availableBLs))
                                            @foreach($availableBLs as $bl)
                                                <flux:select.option value="{{ $bl->id }}">
                                                    {{ $bl->bl_number }} - {{ $bl->ship_name ?? 'N/A' }}
                                                </flux:select.option>
                                            @endforeach
                                        @endif
                                    </flux:select>
                                    @if(empty($availableBLs) && $client_id)
                                        <flux:description>Aucun BL trouvé pour ce client</flux:description>
                                    @elseif(empty($client_id))
                                        <flux:description>Sélectionnez d'abord un client pour voir les BL disponibles</flux:description>
                                    @endif
                                    <flux:error name="bils_ofladings_id" />
                                </flux:field>
                            </div>
                                        @endforeach
                                    </flux:select>
                                    <flux:error name="bils_ofladings_id" />
                                    <flux:description>Associez ce suivie à un BL existant si applicable</flux:description>
                                </flux:field>
                            </div>
                        </div>
                    </div>

                    {{-- Section 2: Informations de Suivi --}}
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <flux:icon.calendar class="size-5 mr-2 text-green-600" />
                            Informations de Suivi
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {{-- Agent de Transit --}}
                            <div>
                                <flux:field>
                                    <flux:label>Agent de Transit *</flux:label>
                                    <flux:select wire:model="agent_transit_id" placeholder="Sélectionnez un agent" required>
                                        @foreach($agentsTransit as $agent)
                                            <flux:select.option value="{{ $agent->id }}">{{ $agent->name }}</flux:select.option>
                                        @endforeach
                                    </flux:select>
                                    <flux:error name="agent_transit_id" />
                                    <flux:description>Agent responsable du suivi</flux:description>
                                </flux:field>
                            </div>

                            {{-- ETA --}}
                            <div>
                                <flux:field>
                                    <flux:label>ETA (Date d'arrivée estimée) *</flux:label>
                                    <flux:input 
                                        type="date" 
                                        wire:model.blur="ETA" 
                                        required
                                        min="{{ date('Y-m-d') }}"
                                    />
                                    <flux:error name="ETA" />
                                    <flux:description>Date prévue d'arrivée du navire</flux:description>
                                </flux:field>
                            </div>

                            {{-- État initial --}}
                            <div>
                                <flux:field>
                                    <flux:label>État initial</flux:label>
                                    <flux:select wire:model="etat" placeholder="Sélectionnez l'état">
                                        <flux:select.option value="en_cours">
                                            <div class="flex items-center">
                                                <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                                                En cours
                                            </div>
                                        </flux:select.option>
                                        <flux:select.option value="arrive">
                                            <div class="flex items-center">
                                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                                Arrivé
                                            </div>
                                        </flux:select.option>
                                        <flux:select.option value="retard">
                                            <div class="flex items-center">
                                                <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                                En retard
                                            </div>
                                        </flux:select.option>
                                    </flux:select>
                                    <flux:error name="etat" />
                                    <flux:description>État actuel du navire</flux:description>
                                </flux:field>
                            </div>
                        </div>
                    </div>

                    {{-- Section 3: Statut Automatique --}}
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <flux:icon.cog class="size-5 mr-2 text-purple-600" />
                            Statut Automatique
                        </h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <flux:field>
                                <flux:label>Statut (Calculé automatiquement)</flux:label>
                                <div class="mt-2">
                                    @if($ETA)
                                        @php
                                            $joursRestants = (int) round(\Carbon\Carbon::parse($ETA)->diffInDays(now(), false));
                                        @endphp
                                        <div class="flex items-center space-x-4">
                                            @if($joursRestants <= 7)
                                                <flux:badge variant="warning" class="text-sm">
                                                    <flux:icon.exclamation-triangle class="size-4 mr-1" />
                                                    Traitement (≤ 7 jours)
                                                </flux:badge>
                                            @else
                                                <flux:badge variant="info" class="text-sm">
                                                    <flux:icon.archive-box class="size-4 mr-1" />
                                                    Stockage (> 7 jours)
                                                </flux:badge>
                                            @endif
                                            <span class="text-sm text-gray-600">
                                                @if($joursRestants < 0)
                                                    {{ abs($joursRestants) }} jour(s) de retard
                                                @elseif($joursRestants == 0)
                                                    Arrivée aujourd'hui
                                                @else
                                                    {{ $joursRestants }} jour(s) restants
                                                @endif
                                            </span>
                                        </div>
                                    @else
                                        <div class="flex items-center text-gray-500">
                                            <flux:icon.clock class="size-4 mr-2" />
                                            <span>Sera déterminé automatiquement après sélection de l'ETA</span>
                                        </div>
                                    @endif
                                </div>
                            </flux:field>
                        </div>
                    </div>

                    {{-- Informations détaillées (si ETA sélectionnée) --}}
                    @if($ETA)
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-blue-900 mb-3 flex items-center">
                                <flux:icon.information-circle class="size-4 mr-2" />
                                Informations calculées
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div class="flex flex-col">
                                    <span class="text-blue-700 font-medium">Jours restants</span>
                                    @php
                                        $joursRestants = (int) round(\Carbon\Carbon::parse($ETA)->diffInDays(now(), false));
                                    @endphp
                                    <span class="text-gray-900 mt-1">
                                        @if($joursRestants < 0)
                                            <span class="text-red-600 font-semibold">{{ abs($joursRestants) }} jour(s) de retard</span>
                                        @elseif($joursRestants == 0)
                                            <span class="text-green-600 font-semibold">Aujourd'hui</span>
                                        @else
                                            <span class="font-semibold">{{ $joursRestants }} jour(s)</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-blue-700 font-medium">Alerte nécessaire</span>
                                    <span class="text-gray-900 mt-1 font-semibold">
                                        {{ $joursRestants <= 7 ? '🔔 Oui' : '✅ Non' }}
                                    </span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-blue-700 font-medium">Statut assigné</span>
                                    <span class="text-gray-900 mt-1 font-semibold">
                                        {{ $joursRestants <= 7 ? '⚡ Traitement' : '📦 Stockage' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                    <flux:button type="button" wire:click="resetForm" variant="ghost">
                        Réinitialiser
                    </flux:button>

                    <div class="flex items-center space-x-3">
                        <flux:button type="button" href="{{ route('suivies.index') }}" variant="ghost">
                            Annuler
                        </flux:button>
                        <flux:button type="submit" variant="primary">
                            <flux:icon.check class="size-4" />
                            Créer le suivie
                        </flux:button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Aide --}}
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex">
                <flux:icon.information-circle class="size-5 text-yellow-600 mt-0.5" />
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Informations importantes</h3>
                    <div class="mt-2 text-sm text-yellow-700">
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

    {{-- Script pour les toasts --}}
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('toast', (event) => {
                const toast = document.createElement('div');
                toast.className = `alert alert-${event.type} mb-2 p-4 rounded-lg shadow-lg`;
                toast.innerHTML = `
                    <div class="flex items-center">
                        <span class="mr-2">${event.type === 'success' ? '✓' : '⚠'}</span>
                        <span>${event.message}</span>
                        <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-lg">&times;</button>
                    </div>
                `;
                
                document.getElementById('toast-container').appendChild(toast);
                
                setTimeout(() => {
                    toast.remove();
                }, 5000);
            });
        });
    </script>
</div>
