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
            $table->foreignId('package_id')->nullable()->change();

            $table->foreignId('hotel_id')->nullable()->after('package_id')->constrained()->nullOnDelete();
            $table->foreignId('hotel_room_id')->nullable()->after('hotel_id')->constrained()->nullOnDelete();
            $table->date('check_in')->nullable()->after('travel_date');
            $table->date('check_out')->nullable()->after('check_in');
            $table->unsignedSmallInteger('nights')->nullable()->after('check_out');
            $table->unsignedSmallInteger('rooms')->nullable()->after('nights');
            $table->unsignedSmallInteger('adults')->nullable()->after('rooms');
            $table->unsignedSmallInteger('children')->nullable()->after('adults');

            $table->index(['hotel_room_id', 'check_in', 'check_out']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex(['hotel_room_id', 'check_in', 'check_out']);
            $table->dropConstrainedForeignId('hotel_room_id');
            $table->dropConstrainedForeignId('hotel_id');
            $table->dropColumn(['check_in', 'check_out', 'nights', 'rooms', 'adults', 'children']);
        });
    }
};
