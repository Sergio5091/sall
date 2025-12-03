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
        Schema::table('salles', function (Blueprint $table) {
            // Colonnes manquantes du modèle
            $table->string('slug')->unique()->after('nom');
            $table->string('type')->nullable()->after('description');
            $table->string('categorie')->nullable()->after('type');
            $table->string('region')->nullable()->after('ville');
            $table->string('departement')->nullable()->after('region');
            $table->string('quartier')->nullable()->after('departement');
            $table->string('whatsapp')->nullable()->after('telephone');
            $table->json('reseaux_sociaux')->nullable()->after('site_web');
            $table->integer('capacite_max')->default(0)->change(); // Renommer capacite_max en capacite
            $table->decimal('surface_area', 8, 2)->nullable()->after('capacite_max');
            $table->integer('machines_arcade')->default(0)->after('surface_area');
            $table->integer('casques_vr')->default(0)->after('machines_arcade');
            $table->integer('flippers')->default(0)->after('casques_vr');
            $table->integer('consoles_retro')->default(0)->after('flippers');
            $table->integer('pc_gaming')->default(0)->after('consoles_retro');
            $table->integer('tables_bowling')->default(0)->after('pc_gaming');
            $table->integer('tables_billard')->default(0)->after('tables_bowling');
            
            // Colonnes booléennes pour les services
            $table->boolean('wifi_gratuit')->default(false)->after('tables_billard');
            $table->boolean('parking')->default(false)->after('wifi_gratuit');
            $table->boolean('climatisation')->default(false)->after('parking');
            $table->boolean('accessibilite_pmr')->default(false)->after('climatisation');
            $table->boolean('surveillance_24h')->default(false)->after('accessibilite_pmr');
            $table->boolean('snack_bar')->default(false)->after('surveillance_24h');
            $table->boolean('restaurant')->default(false)->after('snack_bar');
            $table->boolean('bar')->default(false)->after('restaurant');
            $table->boolean('terrasse')->default(false)->after('bar');
            $table->boolean('espace_fumeur')->default(false)->after('terrasse');
            $table->boolean('vestiaires')->default(false)->after('espace_fumeur');
            
            // Colonnes pour les horaires et médias
            $table->json('horaires_ouverture')->nullable()->after('vestiaires');
            $table->json('jours_fermes')->nullable()->after('horaires_ouverture');
            $table->string('image_couverture')->nullable()->after('jours_fermes');
            $table->json('images_galerie')->nullable()->after('image_couverture');
            $table->string('video_presentation')->nullable()->after('images_galerie');
            $table->json('images_360')->nullable()->after('video_presentation');
            
            // Colonnes SEO et évaluation
            $table->string('meta_titre')->nullable()->after('images_360');
            $table->text('meta_description')->nullable()->after('meta_titre');
            $table->json('mots_cles')->nullable()->after('meta_description');
            
            // Colonnes de statut et validation
            $table->string('statut', 20)->default('actif')->change(); // Modifier le type enum
            $table->boolean('valide_par_admin')->default(false)->after('statut');
            $table->timestamp('date_validation')->nullable()->after('valide_par_admin');
            $table->string('motif_rejet')->nullable()->after('date_validation');
            
            // Colonnes de tarifs et réservation
            $table->json('tarifs')->nullable()->after('motif_rejet');
            $table->boolean('reservation_en_ligne')->default(false)->after('tarifs');
            $table->boolean('paiement_en_ligne')->default(false)->after('reservation_en_ligne');
            
            // Colonnes d'évaluation
            $table->decimal('note_moyenne', 3, 2)->default(0)->after('paiement_en_ligne');
            $table->integer('nombre_avis')->default(0)->after('note_moyenne');
            $table->integer('nombre_vues')->default(0)->after('nombre_avis');
            $table->integer('nombre_favoris')->default(0)->after('nombre_vues');
            
            // Colonnes de géolocalisation
            $table->integer('rayon_action_km')->nullable()->after('nombre_favoris');
            $table->json('zones_couvertes')->nullable()->after('rayon_action_km');
            $table->string('point_repere')->nullable()->after('zones_couvertes');
            
            // Modifier les colonnes existantes
            $table->string('pays')->default('Sénégal')->change();
            $table->decimal('prix_journee', 8, 2)->nullable()->renameTo('prix_jour');
            
            // Index
            $table->index(['slug']);
            $table->index(['categorie', 'type']);
            $table->index(['statut', 'valide_par_admin']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salles', function (Blueprint $table) {
            // Supprimer les colonnes ajoutées
            $table->dropColumn([
                'slug', 'type', 'categorie', 'region', 'departement', 'quartier',
                'whatsapp', 'reseaux_sociaux', 'surface_area', 'machines_arcade',
                'casques_vr', 'flippers', 'consoles_retro', 'pc_gaming', 'tables_bowling',
                'tables_billard', 'wifi_gratuit', 'parking', 'climatisation',
                'accessibilite_pmr', 'surveillance_24h', 'snack_bar', 'restaurant',
                'bar', 'terrasse', 'espace_fumeur', 'vestiaires', 'horaires_ouverture',
                'jours_fermes', 'image_couverture', 'images_galerie', 'video_presentation',
                'images_360', 'meta_titre', 'meta_description', 'mots_cles',
                'valide_par_admin', 'date_validation', 'motif_rejet', 'tarifs',
                'reservation_en_ligne', 'paiement_en_ligne', 'note_moyenne',
                'nombre_avis', 'nombre_vues', 'nombre_favoris', 'rayon_action_km',
                'zones_couvertes', 'point_repere'
            ]);
            
            // Renommer la colonne
            $table->renameColumn('prix_jour', 'prix_journee');
            
            // Modifier les colonnes
            $table->string('pays')->default('France')->change();
            $table->enum('statut', ['actif', 'inactif', 'maintenance'])->default('actif')->change();
            $table->integer('capacite_max')->default(0)->change();
            
            // Supprimer les index
            $table->dropIndex(['slug']);
            $table->dropIndex(['categorie', 'type']);
            $table->dropIndex(['statut', 'valide_par_admin']);
        });
    }
};
