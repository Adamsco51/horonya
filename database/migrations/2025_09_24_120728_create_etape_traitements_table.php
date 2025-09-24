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
        Schema::create('etape_traitements', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // Nom de l'étape (Tirage, Génération SYDAM, Facturation, etc.)
            $table->text('description')->nullable(); // Description de l'étape
            $table->integer('ordre')->default(0); // Ordre d'exécution des étapes
            $table->boolean('obligatoire')->default(true); // Si l'étape est obligatoire
            $table->boolean('active')->default(true); // Si l'étape est active
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etape_traitements');
    }
};
