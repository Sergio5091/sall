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
            // Colonnes manquantes du modèlesss
            if (!Schema::hasColumn('salles', 'slug')) {
                $table->string('slug')->unique()->after('nom');
            }
            if (!Schema::hasColumn('salles', 'type')) {
                $table->string('type')->nullable()->after('description');
            }
            if (!Schema::hasColumn('salles', 'categorie')) {
                $table->string('categorie')->nullable()->after('type');
            }
            if (!Schema::hasColumn('salles', 'region')) {
                $table->string('region')->nullable()->after('ville');
            }
            if (!Schema::hasColumn('salles', 'departement')) {
                $table->string('departement')->nullable()->after('region');
            }
            if (!Schema::hasColumn('salles', 'quartier')) {
                $table->string('quartier')->nullable()->after('departement');
            }
            if (!Schema::hasColumn('salles', 'whatsapp')) {
                $table->string('whatsapp')->nullable()->after('telephone');
            }
            if (!Schema::hasColumn('salles', 'reseaux_sociaux')) {
                $table->json('reseaux_sociaux')->nullable()->after('site_web');
            }
            if (!Schema::hasColumn('salles', 'surface_area')) {
                $table->decimal('surface_area', 8, 2)->nullable()->after('capacite_max');
            }
            if (!Schema::hasColumn('salles', 'machines_arcade')) {
                $table->integer('machines_arcade')->default(0)->after('surface_area');
            }
            if (!Schema::hasColumn('salles', 'casques_vr')) {
                $table->integer('casques_vr')->default(0)->after('machines_arcade');
            }
            if (!Schema::hasColumn('salles', 'flippers')) {
                $table->integer('flippers')->default(0)->after('casques_vr');
            }
            if (!Schema::hasColumn('salles', 'consoles_retro')) {
                $table->integer('consoles_retro')->default(0)->after('flippers');
            }
            if (!Schema::hasColumn('salles', 'pc_gaming')) {
                $table->integer('pc_gaming')->default(0)->after('consoles_retro');
            }
            if (!Schema::hasColumn('salles', 'tables_bowling')) {
                $table->integer('tables_bowling')->default(0)->after('pc_gaming');
            }
            if (!Schema::hasColumn('salles', 'tables_billard')) {
                $table->integer('tables_billard')->default(0)->after('tables_bowling');
            }
            
            // Colonnes booléennes pour les services
            if (!Schema::hasColumn('salles', 'wifi_gratuit')) {
                $table->boolean('wifi_gratuit')->default(false)->after('tables_billard');
            }
            if (!Schema::hasColumn('salles', 'parking')) {
                $table->boolean('parking')->default(false)->after('wifi_gratuit');
            }
            if (!Schema::hasColumn('salles', 'climatisation')) {
                $table->boolean('climatisation')->default(false)->after('parking');
            }
            if (!Schema::hasColumn('salles', 'accessibilite_pmr')) {
                $table->boolean('accessibilite_pmr')->default(false)->after('climatisation');
            }
            if (!Schema::hasColumn('salles', 'surveillance_24h')) {
                $table->boolean('surveillance_24h')->default(false)->after('accessibilite_pmr');
            }
            if (!Schema::hasColumn('salles', 'snack_bar')) {
                $table->boolean('snack_bar')->default(false)->after('surveillance_24h');
            }
            if (!Schema::hasColumn('salles', 'restaurant')) {
                $table->boolean('restaurant')->default(false)->after('snack_bar');
            }
            if (!Schema::hasColumn('salles', 'bar')) {
                $table->boolean('bar')->default(false)->after('restaurant');
            }
            if (!Schema::hasColumn('salles', 'terrasse')) {
                $table->boolean('terrasse')->default(false)->after('bar');
            }
            if (!Schema::hasColumn('salles', 'espace_fumeur')) {
                $table->boolean('espace_fumeur')->default(false)->after('terrasse');
            }
            if (!Schema::hasColumn('salles', 'vestiaires')) {
                $table->boolean('vestiaires')->default(false)->after('espace_fumeur');
            }
            
            // Colonnes pour les horaires et médias
            if (!Schema::hasColumn('salles', 'horaires_ouverture')) {
                $table->json('horaires_ouverture')->nullable()->after('vestiaires');
            }
            if (!Schema::hasColumn('salles', 'jours_fermes')) {
                $table->json('jours_fermes')->nullable()->after('horaires_ouverture');
            }
            if (!Schema::hasColumn('salles', 'image_couverture')) {
                $table->string('image_couverture')->nullable()->after('jours_fermes');
            }
            if (!Schema::hasColumn('salles', 'images_galerie')) {
                $table->json('images_galerie')->nullable()->after('image_couverture');
            }
            if (!Schema::hasColumn('salles', 'video_presentation')) {
                $table->string('video_presentation')->nullable()->after('images_galerie');
            }
            if (!Schema::hasColumn('salles', 'images_360')) {
                $table->json('images_360')->nullable()->after('video_presentation');
            }
            
            // Colonnes SEO et évaluation
            if (!Schema::hasColumn('salles', 'meta_titre')) {
                $table->string('meta_titre')->nullable()->after('images_360');
            }
            if (!Schema::hasColumn('salles', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_titre');
            }
            if (!Schema::hasColumn('salles', 'mots_cles')) {
                $table->json('mots_cles')->nullable()->after('meta_description');
            }
            
            // Colonnes de statut et validation
            if (!Schema::hasColumn('salles', 'valide_par_admin')) {
                $table->boolean('valide_par_admin')->default(false)->after('statut');
            }
            if (!Schema::hasColumn('salles', 'date_validation')) {
                $table->timestamp('date_validation')->nullable()->after('valide_par_admin');
            }
            if (!Schema::hasColumn('salles', 'motif_rejet')) {
                $table->string('motif_rejet')->nullable()->after('date_validation');
            }
            
            // Colonnes de tarifs et réservation
            if (!Schema::hasColumn('salles', 'tarifs')) {
                $table->json('tarifs')->nullable()->after('motif_rejet');
            }
            if (!Schema::hasColumn('salles', 'reservation_en_ligne')) {
                $table->boolean('reservation_en_ligne')->default(false)->after('tarifs');
            }
            if (!Schema::hasColumn('salles', 'paiement_en_ligne')) {
                $table->boolean('paiement_en_ligne')->default(false)->after('reservation_en_ligne');
            }
            
            // Colonnes d'évaluation
            if (!Schema::hasColumn('salles', 'note_moyenne')) {
                $table->decimal('note_moyenne', 3, 2)->default(0)->after('paiement_en_ligne');
            }
            if (!Schema::hasColumn('salles', 'nombre_avis')) {
                $table->integer('nombre_avis')->default(0)->after('note_moyenne');
            }
            if (!Schema::hasColumn('salles', 'nombre_vues')) {
                $table->integer('nombre_vues')->default(0)->after('nombre_avis');
            }
            if (!Schema::hasColumn('salles', 'nombre_favoris')) {
                $table->integer('nombre_favoris')->default(0)->after('nombre_vues');
            }
            
            // Colonnes de géolocalisation
            if (!Schema::hasColumn('salles', 'rayon_action_km')) {
                $table->integer('rayon_action_km')->nullable()->after('nombre_favoris');
            }
            if (!Schema::hasColumn('salles', 'zones_couvertes')) {
                $table->json('zones_couvertes')->nullable()->after('rayon_action_km');
            }
            if (!Schema::hasColumn('salles', 'point_repere')) {
                $table->string('point_repere')->nullable()->after('zones_couvertes');
            }
            
            // Index
            if (!Schema::hasIndex('salles', 'salles_slug_index')) {
                $table->index(['slug']);
            }
            if (!Schema::hasIndex('salles', 'salles_categorie_type_index')) {
                $table->index(['categorie', 'type']);
            }
            if (!Schema::hasIndex('salles', 'salles_statut_valide_par_admin_index')) {
                $table->index(['statut', 'valide_par_admin']);
            }
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
