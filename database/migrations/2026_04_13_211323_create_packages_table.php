<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();

            $table->json('name');
            $table->string('slug')->unique();

            $table->foreignId('service_id')
                ->nullable()
                ->constrained('services')
                ->nullOnDelete();


            $table->json('description')->nullable();
            $table->json('features')->nullable();

            $table->string('location_label')->nullable();

            $table->decimal('price', 10, 2)->default(0);

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->unsignedSmallInteger('max_guests')->nullable();
            $table->unsignedSmallInteger('days')->nullable();

            $table->string('image_url')->nullable();

            $table->json('tags')->nullable();
            $table->json('highlights')->nullable();
            $table->json('itinerary')->nullable();
            $table->json('included_items')->nullable();
            $table->json('excluded_items')->nullable();
            $table->json('gallery')->nullable();

            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);

            $table->timestamps();

            $table->index(['start_date', 'end_date']);
            $table->index(['is_active', 'sort_order']);
            $table->index('service_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
