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
            $table->string('telephone')->nullable()->after('email');
            $table->text('bio')->nullable()->after('telephone');
            $table->string('profile_photo_path')->nullable()->after('bio');
            $table->json('preferences')->nullable()->after('profile_photo_path');
            $table->timestamp('password_updated_at')->nullable()->after('preferences');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['telephone', 'bio', 'profile_photo_path', 'preferences', 'password_updated_at']);
        });
    }
};
