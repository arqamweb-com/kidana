<?php

use App\Models\Booking;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Give every existing booking a public reference.
     */
    public function up(): void
    {
        DB::table('bookings')
            ->whereNull('reference')
            ->lazyById()
            ->each(fn (object $booking) => DB::table('bookings')
                ->where('id', $booking->id)
                ->update(['reference' => Booking::newReference()]));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
