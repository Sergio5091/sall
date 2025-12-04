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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('salle_id')->constrained()->onDelete('cascade');
            $table->dateTime('date_heure');
            $table->integer('duree'); // en heures
            $table->integer('nombre_personnes');
            $table->decimal('prix_total', 10, 2);
            $table->enum('statut', ['en_attente', 'confirme', 'annule', 'termine'])->default('en_attente');
            $table->text('message')->nullable();
            $table->text('promoter_notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'date_heure']);
            $table->index(['salle_id', 'date_heure']);
            $table->index('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
