<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchPackagesRequest;
use App\Models\Destination;
use App\Models\Package;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;

class PackageSearchController extends Controller
{
    public function __invoke(SearchPackagesRequest $request): View
    {
        $filters = $request->validated();
        $destinationFilter = $filters['destination'] ?? null;

        $packages = Package::query()
            ->with(['destination:id,name,slug'])
            ->active()
            ->when(
                filled($destinationFilter),
                fn(Builder $query) => $query->whereHas(
                    'destination',
                    fn(Builder $query) => $this->filterDestination($query, (string) $destinationFilter),
                ),
            )
            ->when(
                filled($filters['travel_date'] ?? null),
                fn($query) => $query
                    ->whereDate('start_date', '<=', $filters['travel_date'])
                    ->whereDate('end_date', '>=', $filters['travel_date']),
            )
            ->when(
                filled($filters['guests'] ?? null),
                fn($query) => $query->where('max_guests', '>=', (int) $filters['guests']),
            )
            ->orderBy('sort_order')
            ->orderBy('start_date')
            ->orderByDesc('created_at')
            ->paginate(9)
            ->withQueryString();

        $destinations = Destination::query()
            ->active()
            ->orderBy('sort_order')
            ->orderBy('name->' . app()->getLocale())
            ->get(['id', 'name', 'slug']);

        return view('packages.search', [
            'packages' => $packages,
            'filters' => $filters,
            'destinations' => $destinations,
        ]);
    }

    private function filterDestination(Builder $query, string $destination): void
    {
        $locale = app()->getLocale();
        $fallbackLocale = config('app.fallback_locale');

        $query
            ->where('slug', $destination)
            ->orWhere("name->{$locale}", 'like', "%{$destination}%");

        if ($fallbackLocale !== $locale) {
            $query->orWhere("name->{$fallbackLocale}", 'like', "%{$destination}%");
        }
    }
}
