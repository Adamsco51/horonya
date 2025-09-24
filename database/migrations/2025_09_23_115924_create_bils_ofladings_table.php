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
        Schema::create('bils_ofladings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_travail_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('bl_number')->unique();
            $table->integer('nbr_conteneur');
            $table->string('ship_name');
            $table->string('type_of_conteneur');
            $table->string('category');
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bils_ofladings');
        Schema::dropIfExists('type_travails');
        Schema::dropIfExists('clients');
    }
};
