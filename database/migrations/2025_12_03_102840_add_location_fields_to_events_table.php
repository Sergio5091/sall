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
        Schema::table('events', function (Blueprint $table) {
            $table->string('lieu')->nullable()->after('image_banniere');
            $table->string('adresse')->nullable()->after('lieu');
            $table->string('code_postal', 20)->nullable()->after('adresse');
            $table->string('ville', 100)->nullable()->after('code_postal');
            $table->string('pays', 100)->nullable()->after('ville');
            $table->decimal('latitude', 10, 8)->nullable()->after('pays');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->string('site_web')->nullable()->after('reseaux_sociaux');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['lieu', 'adresse', 'code_postal', 'ville', 'pays', 'latitude', 'longitude', 'site_web']);
        });
    }
};
