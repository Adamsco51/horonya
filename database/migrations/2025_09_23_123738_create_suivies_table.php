<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('suivies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bils_ofladings_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');            
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('agent_transit_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('numero_bl')->description('Numéro du Bill of Lading');
            $table->date('ETA')->description('Date d\'arrivée estimée');
            $table->enum('status', ['stockage', 'traitement'])->default('stockage')->description('Statut de traitement');
            $table->enum('etat', ['en_cours', 'arrive', 'retard'])->default('en_cours')->description('État du navire');
            $table->timestamps();
            
            // Index pour optimiser les requêtes
            $table->index(['ETA', 'status']);
            $table->index('agent_transit_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suivies');
    }
};
