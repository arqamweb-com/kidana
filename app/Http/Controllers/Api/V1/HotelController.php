<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\HotelResource;
use App\Models\Hotel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class HotelController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'destination' => ['nullable', 'string', 'max:255'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $hotels = Hotel::query()
            ->active()
            ->with('destination:id,name,slug,image_url')
            ->when($request->filled('destination'), fn (Builder $query) => $query
                ->whereHas('destination', fn (Builder $query) => $query->where('slug', $request->string('destination'))))
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate($request->integer('per_page', 20))
            ->withQueryString();

        return HotelResource::collection($hotels);
    }

    public function show(Hotel $hotel): HotelResource
    {
        abort_unless($hotel->is_active, 404);

        return new HotelResource($hotel->load([
            'destination:id,name,slug,image_url',
            'rooms' => fn ($query) => $query->active()->orderBy('sort_order'),
        ]));
    }
}
