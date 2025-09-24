<div class="container mx-auto px-4 py-6">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Configuration</h1>
        <p class="text-gray-600">Gérez les paramètres et configurations de l'application</p>
    </div>

    <!-- Grille des modules de configuration -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- Types de Travail -->
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-briefcase text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-800">Types de Travail</h3>
                        <p class="text-sm text-gray-600">Gérer les types de travail</p>
                    </div>
                </div>
                <p class="text-gray-600 mb-4">
                    Configurez les différents types de travail disponibles dans le système.
                </p>
                <a href="{{ route('configuration.type-travail') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-md transition-colors duration-200">
                    <i class="fas fa-cog mr-2"></i>
                    Configurer
                </a>
            </div>
        </div>

        <!-- Étapes de Traitement -->
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-list-ol text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-800">Étapes de Traitement</h3>
                        <p class="text-sm text-gray-600">Gérer les étapes</p>
                    </div>
                </div>
                <p class="text-gray-600 mb-4">
                    Définissez et organisez les étapes de traitement des suivis.
                </p>
                <a href="{{ route('configuration.etape-traitement') }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-md transition-colors duration-200">
                    <i class="fas fa-cog mr-2"></i>
                    Configurer
                </a>
            </div>
        </div>

        <!-- Gestion des Documents -->
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fas fa-file-alt text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-800">Documents</h3>
                        <p class="text-sm text-gray-600">Gestion des documents</p>
                    </div>
                </div>
                <p class="text-gray-600 mb-4">
                    Gérez les documents joints aux suivis et traitements.
                </p>
                <a href="{{ route('documents.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white text-sm font-medium rounded-md transition-colors duration-200">
                    <i class="fas fa-folder mr-2"></i>
                    Voir Documents
                </a>
            </div>
        </div>

        <!-- Utilisateurs (placeholder pour future extension) -->
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 opacity-75">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-gray-100 p-3 rounded-full">
                        <i class="fas fa-users text-gray-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-800">Utilisateurs</h3>
                        <p class="text-sm text-gray-600">Gestion des utilisateurs</p>
                    </div>
                </div>
                <p class="text-gray-600 mb-4">
                    Gérez les comptes utilisateurs et leurs permissions.
                </p>
                <button disabled 
                        class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-500 text-sm font-medium rounded-md cursor-not-allowed">
                    <i class="fas fa-lock mr-2"></i>
                    Bientôt disponible
                </button>
            </div>
        </div>

        <!-- Paramètres Système (placeholder pour future extension) -->
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 opacity-75">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-gray-100 p-3 rounded-full">
                        <i class="fas fa-cogs text-gray-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-800">Paramètres Système</h3>
                        <p class="text-sm text-gray-600">Configuration générale</p>
                    </div>
                </div>
                <p class="text-gray-600 mb-4">
                    Configurez les paramètres généraux de l'application.
                </p>
                <button disabled 
                        class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-500 text-sm font-medium rounded-md cursor-not-allowed">
                    <i class="fas fa-lock mr-2"></i>
                    Bientôt disponible
                </button>
            </div>
        </div>

        <!-- Sauvegarde et Maintenance (placeholder pour future extension) -->
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 opacity-75">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-gray-100 p-3 rounded-full">
                        <i class="fas fa-database text-gray-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-800">Maintenance</h3>
                        <p class="text-sm text-gray-600">Sauvegarde et maintenance</p>
                    </div>
                </div>
                <p class="text-gray-600 mb-4">
                    Outils de sauvegarde et de maintenance du système.
                </p>
                <button disabled 
                        class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-500 text-sm font-medium rounded-md cursor-not-allowed">
                    <i class="fas fa-lock mr-2"></i>
                    Bientôt disponible
                </button>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="mt-12">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Aperçu du Système</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-2 rounded">
                        <i class="fas fa-briefcase text-blue-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-600">Types de Travail</p>
                        <p class="text-lg font-semibold">{{ \App\Models\TypeTravail::count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-4 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="bg-green-100 p-2 rounded">
                        <i class="fas fa-list-ol text-green-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-600">Étapes Configurées</p>
                        <p class="text-lg font-semibold">{{ \App\Models\EtapeTraitement::count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-4 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="bg-purple-100 p-2 rounded">
                        <i class="fas fa-file-alt text-purple-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-600">Documents</p>
                        <p class="text-lg font-semibold">{{ \App\Models\Document::count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-4 rounded-lg shadow">
                <div class="flex items-center">
                    <div class="bg-yellow-100 p-2 rounded">
                        <i class="fas fa-ship text-yellow-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-600">Suivis Actifs</p>
                        <p class="text-lg font-semibold">{{ \App\Models\Suivie::where('statut', '!=', 'termine')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
