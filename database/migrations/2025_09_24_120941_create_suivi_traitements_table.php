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
        Schema::create('suivi_traitements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('suivie_id')->constrained('suivies')->onDelete('cascade'); // Référence au suivi
            $table->foreignId('etape_traitement_id')->constrained('etape_traitements')->onDelete('cascade'); // Référence à l'étape
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Utilisateur qui a réalisé l'étape
            $table->datetime('date_realisation'); // Date de réalisation de l'étape
            $table->text('commentaire')->nullable(); // Commentaire sur l'étape
            $table->json('documents')->nullable(); // Documents joints (stockage JSON des chemins)
            $table->enum('statut', ['en_cours', 'termine', 'bloque'])->default('en_cours'); // Statut de l'étape
            $table->timestamps();
            
            // Index pour optimiser les requêtes
            $table->index(['suivie_id', 'etape_traitement_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suivi_traitements');
    }
};
