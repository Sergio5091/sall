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
        Schema::table('products', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->text('description')->after('name');
            $table->decimal('price', 10, 2)->after('description');
            $table->decimal('original_price', 10, 2)->nullable()->after('price');
            $table->string('image')->nullable()->after('original_price');
            $table->json('images')->nullable()->after('image');
            $table->string('category')->after('images');
            $table->integer('stock')->default(0)->after('category');
            $table->boolean('featured')->default(false)->after('stock');
            $table->decimal('rating', 3, 2)->default(0)->after('featured');
            $table->json('specifications')->nullable()->after('rating');
            $table->enum('status', ['active', 'inactive', 'draft'])->default('active')->after('rating');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade')->after('status');

            // Indexes
            $table->index(['category']);
            $table->index(['status']);
            $table->index(['featured']);
            $table->index(['price']);
            $table->index(['created_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'name',
                'description', 
                'price',
                'original_price',
                'image',
                'images',
                'category',
                'stock',
                'featured',
                'rating',
                'specifications',
                'status',
                'created_by'
            ]);
        });
    }
};
