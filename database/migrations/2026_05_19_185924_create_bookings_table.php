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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_mobile');
            $table->unsignedSmallInteger('guests')->nullable();
            $table->date('travel_date')->nullable();
            $table->text('message')->nullable();
            $table->string('locale', 8)->default('en');
            $table->string('type')->default('custom_form');
            $table->string('status')->default('pending');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('currency', 3)->default('EGP');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('payment_success_email_sent_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('package_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
