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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();

            $table->json('name');
            $table->string('slug')->unique();

            $table->foreignId('destination_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->json('description')->nullable();
            $table->json('address')->nullable();
            $table->unsignedTinyInteger('stars')->nullable();
            $table->string('map_url', 2048)->nullable();

            $table->string('board_basis')->nullable();
            $table->string('check_in_time', 5)->nullable();
            $table->string('check_out_time', 5)->nullable();
            $table->json('cancellation_policy')->nullable();

            $table->string('order_action')->default('custom_form');

            $table->string('image_url')->nullable();
            $table->json('gallery')->nullable();
            $table->json('amenities')->nullable();
            $table->json('policies')->nullable();
            $table->json('tags')->nullable();

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
        Schema::dropIfExists('hotels');
    }
};
