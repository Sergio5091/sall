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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            
            // Relations
            $table->foreignId('salle_id')->constrained('salles')->onDelete('cascade');
            $table->foreignId('promoter_id')->constrained('users')->onDelete('cascade');
            
            // Informations de base
            $table->string('titre');
            $table->text('description');
            $table->string('slug')->unique();
            $table->string('image_affiche')->nullable();
            $table->string('image_banniere')->nullable();
            
            // Dates et heures
            $table->dateTime('date_debut');
            $table->dateTime('date_fin');
            $table->date('date_limite_inscription')->nullable();
            
            // Tarifs
            $table->decimal('prix_base', 10, 2)->default(0);
            $table->decimal('prix_vip', 10, 2)->nullable();
            $table->decimal('prix_groupe', 10, 2)->nullable();
            $table->string('devise', 3)->default('XOF');
            $table->boolean('gratuit')->default(false);
            
            // Capacité
            $table->integer('capacite_max')->nullable();
            $table->integer('places_disponibles')->nullable();
            $table->boolean('limite_inscription')->default(false);
            
            // Catégorie et type
            $table->string('categorie'); // tournoi, soiree, atelier, lancement, etc.
            $table->string('type'); // online, offline, hybride
            $table->json('tags')->nullable();
            
            // Public cible
            $table->string('public_cible')->nullable(); // tous, 18+, 21+, etc.
            $table->integer('age_minimum')->nullable();
            
            // Contenu et programme
            $table->json('programme')->nullable(); // horaires détaillés
            $table->json('activites')->nullable(); // liste des activités
            $table->json('prix_speciaux')->nullable(); // prix pour groupes, étudiants, etc.
            
            // Réseaux sociaux et contact
            $table->string('contact_email')->nullable();
            $table->string('contact_telephone')->nullable();
            $table->string('contact_whatsapp')->nullable();
            $table->json('reseaux_sociaux')->nullable();
            
            // Configuration
            $table->boolean('inscription_obligatoire')->default(true);
            $table->boolean('paiement_en_ligne')->default(false);
            $table->boolean('certificat_participation')->default(false);
            $table->boolean('streaming')->default(false);
            $table->string('url_streaming')->nullable();
            
            // Statut et visibilité
            $table->enum('statut', ['brouillon', 'publie', 'annule', 'termine'])->default('brouillon');
            $table->enum('visibilite', ['public', 'prive', 'invite_seulement'])->default('public');
            $table->boolean('mis_en_avant')->default(false);
            
            // SEO
            $table->string('meta_titre')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('mots_cles')->nullable();
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Index
            $table->index(['salle_id', 'date_debut']);
            $table->index(['promoter_id', 'statut']);
            $table->index(['categorie', 'type']);
            $table->index(['date_debut', 'statut']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
