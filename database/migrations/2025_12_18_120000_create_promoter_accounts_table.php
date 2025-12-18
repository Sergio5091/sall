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
        Schema::create('promoter_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('linked_user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_active')->default(false); // Compte actuellement actif
            $table->string('nickname')->nullable(); // Nom d'affichage pour ce compte
            $table->timestamps();
            
            // Un promoteur ne peut avoir qu'un seul compte actif à la fois
            $table->unique(['main_user_id', 'is_active'], 'unique_active_account');
            
            // Éviter les doublons
            $table->unique(['main_user_id', 'linked_user_id'], 'unique_promoter_account');
            
            $table->index(['main_user_id', 'is_active']);
            $table->index(['linked_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promoter_accounts');
    }
};
