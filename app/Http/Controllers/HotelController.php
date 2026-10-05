<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Hotel;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function __invoke(Request $request): View
    {
        $destinationSlug = $request->string('destination')->toString();
        $stars = $request->integer('stars');

        return view('pages.hotels', [
            'hotels' => Hotel::query()
                ->with('destination:id,name,slug')
                ->withMin(['rooms as starting_price' => fn (Builder $query) => $query->active()], 'price_per_night')
                ->active()
                ->when($destinationSlug !== '', fn (Builder $query) => $query->whereRelation('destination', 'slug', $destinationSlug))
                ->when($stars > 0, fn (Builder $query) => $query->where('stars', $stars))
                ->orderBy('sort_order')
                ->orderByDesc('created_at')
                ->paginate(9)
                ->withQueryString(),
            'destinations' => Destination::query()
                ->active()
                ->whereHas('hotels', fn (Builder $query) => $query->active())
                ->orderBy('sort_order')
                ->get(['id', 'name', 'slug']),
            'selectedDestination' => $destinationSlug,
            'selectedStars' => $stars,
        ]);
    }
}
