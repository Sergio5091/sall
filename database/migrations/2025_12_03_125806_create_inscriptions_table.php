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
        Schema::create('inscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('statut')->default('en_attente'); // en_attente, confirme, annule
            $table->datetime('date_inscription');
            $table->string('paiement_statut')->default('non_paye'); // non_paye, paye, rembourse
            $table->decimal('montant_paye', 8, 2)->default(0);
            $table->timestamps();
            
            $table->index(['event_id', 'statut']);
            $table->index(['user_id', 'event_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscriptions');
    }
};
