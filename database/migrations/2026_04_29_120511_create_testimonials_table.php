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
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->json('testimonial');
            $table->json('position');
            $table->json('tags')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });

        Schema::create('faq_package', function (Blueprint $table) {
            $table->foreignId('faq_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->primary(['faq_id', 'package_id']);
        });

        Schema::create('faq_service', function (Blueprint $table) {
            $table->foreignId('faq_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->primary(['faq_id', 'service_id']);
        });

        Schema::create('package_testimonial', function (Blueprint $table) {
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->foreignId('testimonial_id')->constrained()->cascadeOnDelete();
            $table->primary(['package_id', 'testimonial_id']);
        });

        Schema::create('service_testimonial', function (Blueprint $table) {
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->foreignId('testimonial_id')->constrained()->cascadeOnDelete();
            $table->primary(['service_id', 'testimonial_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_testimonial');
        Schema::dropIfExists('package_testimonial');
        Schema::dropIfExists('faq_service');
        Schema::dropIfExists('faq_package');
        Schema::dropIfExists('testimonials');
    }
};
