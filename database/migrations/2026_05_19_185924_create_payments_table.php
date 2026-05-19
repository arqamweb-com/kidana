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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('provider')->default('fawry');
            $table->string('merchant_ref_number')->unique();
            $table->string('fawry_reference_number')->nullable()->index();
            $table->string('payment_method')->default('PayAtFawry');
            $table->string('status')->default('pending');
            $table->decimal('amount', 10, 2);
            $table->decimal('fawry_fees', 10, 2)->nullable();
            $table->string('currency', 3)->default('EGP');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();
            $table->json('webhook_payload')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('booking_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
