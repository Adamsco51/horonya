<?php

use \App\Livewire\Suivie\CreateSuivie;
use \App\Livewire\Suivie\EditSuivie;
use \App\Livewire\Suivie\IndexSuivie;
use \App\Livewire\Suivie\ShowSuivie;
use App\Http\Controllers\DocumentController;
use App\Livewire\Admin\UserManagement;
use App\Livewire\BL\CreateBL;
use App\Livewire\BL\EditBL;
use App\Livewire\BL\IndexBL;
use App\Livewire\BL\ShowBL;
use App\Livewire\Client\CreateClient;
use App\Livewire\Client\EditClient;
use App\Livewire\Client\IndexClient;
use App\Livewire\Client\ShowClient;
use App\Livewire\Configuration\EtapeTraitement as ConfigurationEtapeTraitement;
use App\Livewire\Configuration\Index as ConfigurationIndex;
use App\Livewire\Configuration\TypeTravail as ConfigurationTypeTravail;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\TypeTravail\CreateTypeTravail;
use App\Livewire\TypeTravail\EditTypeTravail;
use App\Livewire\TypeTravail\IndexTypeTravail;
use App\Livewire\TypeTravail\ShowTypeTravail;
use Illuminate\Support\Facades\Route;




Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::middleware(['auth', 'verified'])->group(function () {
    // Routes pour les Bills of Lading (BL)
    Route::get('bl', IndexBL::class)->name('bl.index');
    Route::get('bl/create', CreateBL::class)->name('bl.create');
    Route::get('bl/{id}/edit', EditBL::class)->name('bl.edit');
    Route::get('bl/{id}', ShowBL::class)->name('bl.show');

    // Routes pour les Clients
    Route::get('clients', IndexClient::class)->name('clients.index');
    Route::get('clients/create', CreateClient::class)->name('clients.create');
    Route::get('clients/{id}/edit', EditClient::class)->name('clients.edit');
    Route::get('clients/{id}', ShowClient::class)->name('clients.show');

    // Routes pour les suivies
    Route::get('suivies', IndexSuivie::class)->name('suivies.index');
    Route::get('suivies/create', CreateSuivie::class)->name('suivies.create');
    Route::get('suivies/{suivie}', ShowSuivie::class)->name('suivies.show');
    Route::get('suivies/{suivie}/edit', EditSuivie::class)->name('suivies.edit');

    // Routes pour les Types de Travail
    Route::get('type-travail', IndexTypeTravail::class)->name('type-travail.index');
    Route::get('type-travail/create', CreateTypeTravail::class)->name('type-travail.create');
    Route::get('type-travail/{typeTravail}/edit', EditTypeTravail::class)->name('type-travail.edit');
    Route::get('type-travail/{typeTravail}', ShowTypeTravail::class)->name('type-travail.show');

    // Routes pour la Configuration
    Route::get('configuration', ConfigurationIndex::class)->name('configuration.index');
    Route::get('configuration/type-travail', ConfigurationTypeTravail::class)->name('configuration.type-travail');
    Route::get('configuration/etape-traitement', ConfigurationEtapeTraitement::class)->name('configuration.etape-traitement');

    // Routes d'administration (réservées aux admin et manager)
    Route::middleware(['role:admin,manager'])->group(function () {
        Route::get('admin/users', UserManagement::class)->name('admin.users');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

// Routes pour les documents
Route::middleware(['auth'])->group(function () {
    Route::get('documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::get('documents/{document}/view', [DocumentController::class, 'view'])->name('documents.view');
    Route::post('documents/upload', [DocumentController::class, 'upload'])->name('documents.upload');
    Route::delete('documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    Route::get('documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
});

require __DIR__.'/auth.php';
