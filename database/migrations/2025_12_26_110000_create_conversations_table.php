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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('Client');
            $table->foreignId('promoter_id')->constrained('users')->onDelete('cascade')->comment('Promoteur');
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'promoter_id']);
            $table->index(['reservation_id']);
            $table->index(['last_message_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
