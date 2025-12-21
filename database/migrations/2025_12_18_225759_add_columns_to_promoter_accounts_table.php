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
        Schema::table('promoter_accounts', function (Blueprint $table) {
            // Vérifier si les colonnes existent avant de les ajouter
            if (!Schema::hasColumn('promoter_accounts', 'main_user_id')) {
                $table->foreignId('main_user_id')->constrained('users')->onDelete('cascade');
            }
            if (!Schema::hasColumn('promoter_accounts', 'linked_user_id')) {
                $table->foreignId('linked_user_id')->constrained('users')->onDelete('cascade');
            }
            if (!Schema::hasColumn('promoter_accounts', 'nickname')) {
                $table->string('nickname')->nullable();
            }
            if (!Schema::hasColumn('promoter_accounts', 'is_active')) {
                $table->boolean('is_active')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promoter_accounts', function (Blueprint $table) {
            $table->dropForeign(['main_user_id']);
            $table->dropForeign(['linked_user_id']);
            $table->dropColumn(['nickname', 'is_active']);
        });
    }
};
