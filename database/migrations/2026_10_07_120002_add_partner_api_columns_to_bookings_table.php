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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('reference', 20)->nullable()->unique()->after('id');
            $table->string('source', 10)->default('web')->after('type')->index();
            $table->foreignId('api_client_id')->nullable()->after('source')->constrained()->nullOnDelete();
            $table->string('external_reference')->nullable()->after('api_client_id');
            $table->string('idempotency_key')->nullable()->after('external_reference');

            $table->unique(['api_client_id', 'idempotency_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropUnique(['api_client_id', 'idempotency_key']);
            $table->dropConstrainedForeignId('api_client_id');
            $table->dropIndex(['source']);
            $table->dropUnique(['reference']);
            $table->dropColumn(['reference', 'source', 'external_reference', 'idempotency_key']);
        });
    }
};
