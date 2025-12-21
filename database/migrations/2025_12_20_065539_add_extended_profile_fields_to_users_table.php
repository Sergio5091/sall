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
        Schema::table('users', function (Blueprint $table) {
            $table->string('company')->nullable()->after('bio');
            $table->string('experience')->nullable()->after('company');
            $table->text('favorite_games')->nullable()->after('experience');
            $table->json('specialties')->nullable()->after('favorite_games');
            $table->json('social_links')->nullable()->after('specialties');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['company', 'experience', 'favorite_games', 'specialties', 'social_links']);
        });
    }
};
