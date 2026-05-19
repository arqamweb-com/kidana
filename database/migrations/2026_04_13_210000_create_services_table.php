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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('slug')->unique();
            $table->json('description')->nullable();
            $table->json('hero_title')->nullable();
            $table->json('hero_subtitle')->nullable();
            $table->json('hero_description')->nullable();
            $table->string('hero_image')->nullable();
            $table->json('intro_subtitle')->nullable();
            $table->json('intro_title')->nullable();
            $table->json('intro_text')->nullable();
            $table->json('stats')->nullable();
            $table->json('benefits')->nullable();
            $table->json('steps')->nullable();
            $table->json('gallery')->nullable();
            $table->json('cta_title')->nullable();
            $table->json('cta_subtitle')->nullable();
            $table->string('icon')->nullable();
            $table->string('image_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
