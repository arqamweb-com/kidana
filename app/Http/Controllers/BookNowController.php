<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Package;
use App\Models\Service;
use Illuminate\Contracts\View\View;

class BookNowController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): View
    {
        $locale = app()->getLocale();

        return view('pages.book-now', [
            'destinations' => Destination::query()
                ->active()
                ->orderBy('sort_order')
                ->orderBy("name->{$locale}")
                ->get(['id', 'name', 'slug', 'image_url']),
            'packages' => Package::query()
                ->with(['destination:id,name,slug', 'service:id,name'])
                ->active()
                ->orderBy('sort_order')
                ->orderBy('start_date')
                ->orderByDesc('created_at')
                ->get(),
            'services' => Service::query()
                ->active()
                ->orderBy('sort_order')
                ->orderBy("name->{$locale}")
                ->get(),
        ]);
    }
}
