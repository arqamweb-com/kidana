<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Contracts\View\View;

class DestinationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $locale, Destination $destination): View
    {
        abort_unless($destination->is_active, 404);

        return view('destinations.show', [
            'destination' => $destination,
            'packages' => $destination->packages()
                ->with(['destination:id,name,slug', 'service:id,name'])
                ->active()
                ->orderBy('sort_order')
                ->orderBy('start_date')
                ->orderByDesc('created_at')
                ->get(),
        ]);
    }
}
