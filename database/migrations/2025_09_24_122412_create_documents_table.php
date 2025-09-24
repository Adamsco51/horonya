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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('nom_original'); // Nom original du fichier
            $table->string('nom_stockage'); // Nom du fichier sur le serveur
            $table->string('chemin'); // Chemin complet du fichier
            $table->string('type_mime'); // Type MIME du fichier
            $table->unsignedBigInteger('taille'); // Taille en octets
            $table->string('extension'); // Extension du fichier
            $table->morphs('documentable'); // Relation polymorphe (crée automatiquement l'index)
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Utilisateur qui a uploadé
            $table->text('description')->nullable(); // Description optionnelle
            $table->timestamps();

            // Index pour améliorer les performances
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
