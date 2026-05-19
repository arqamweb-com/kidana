<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Contracts\View\View;

class DestinationIndexController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.destinations', [
            'destinations' => Destination::query()
                ->active()
                ->orderBy('sort_order')
                ->orderBy('name->' . app()->getLocale())
                ->get(['id', 'name', 'slug', 'image_url']),
        ]);
    }
}
