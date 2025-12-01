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
        Schema::create('salles', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->string('adresse');
            $table->string('ville');
            $table->string('code_postal');
            $table->string('pays')->default('France');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('site_web')->nullable();
            $table->json('horaires')->nullable();
            $table->integer('capacite_max')->default(0);
            $table->decimal('surface', 8, 2)->nullable();
            $table->json('equipements')->nullable();
            $table->json('services')->nullable();
            $table->decimal('prix_heure', 8, 2)->nullable();
            $table->decimal('prix_journee', 8, 2)->nullable();
            $table->string('image_url')->nullable();
            $table->json('images')->nullable();
            $table->enum('statut', ['actif', 'inactif', 'maintenance'])->default('actif');
            $table->boolean('valide')->default(false);
            $table->foreignId('promoter_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['ville', 'statut']);
            $table->index(['promoter_id', 'statut']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salles');
    }
};
