<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Contracts\View\View;

class HotelShowController extends Controller
{
    public function __invoke(string $locale, Hotel $hotel): View
    {
        abort_unless($hotel->is_active, 404);

        $hotel->loadMissing([
            'destination:id,name,slug',
            'rooms' => fn ($query) => $query->active()->orderBy('sort_order')->orderBy('price_per_night'),
            'faqs' => fn ($query) => $query->active()->orderBy('sort_order'),
            'testimonials' => fn ($query) => $query->active()->orderBy('sort_order'),
        ]);

        return view('hotels.show', [
            'hotel' => $hotel,
        ]);
    }
}
