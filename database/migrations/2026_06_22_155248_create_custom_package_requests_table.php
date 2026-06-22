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
        Schema::create('custom_package_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('destination')->nullable();
            $table->string('travel_type')->nullable();
            $table->unsignedSmallInteger('travelers')->nullable();
            $table->date('travel_date')->nullable();
            $table->string('budget')->nullable();
            $table->string('accommodation')->nullable();
            $table->string('duration')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('new');
            $table->string('locale', 8)->default('en');
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_package_requests');
    }
};
