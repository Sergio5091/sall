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
        Schema::create('evenements', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('description');
            $table->dateTime('date_debut');
            $table->dateTime('date_fin');
            $table->string('statut')->default('brouillon');
            $table->foreignId('salle_id')->constrained()->onDelete('cascade');
            $table->string('organisateur')->nullable();
            $table->integer('participants_max')->default(0);
            $table->integer('participants_actuels')->default(0);
            $table->decimal('prix', 8, 2)->nullable();
            $table->string('image_url')->nullable();
            $table->timestamps();
            
            // Index pour optimiser les requêtes
            $table->index(['statut', 'date_debut']);
            $table->index('salle_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evenements');
    }
};
