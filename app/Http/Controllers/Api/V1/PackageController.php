<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ListPackagesRequest;
use App\Http\Resources\Api\V1\PackageResource;
use App\Models\ApiClient;
use App\Models\Package;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PackageController extends Controller
{
    public function index(ListPackagesRequest $request): AnonymousResourceCollection
    {
        $filters = $request->validated();

        /** @var ApiClient $client */
        $client = $request->user();

        $packages = Package::query()
            ->active()
            ->with(['service:id,name,slug', 'destination:id,name,slug,image_url'])
            ->when(filled($filters['service'] ?? null), fn (Builder $query) => $query
                ->whereHas('service', fn (Builder $query) => $query->where('slug', $filters['service'])))
            ->when(filled($filters['destination'] ?? null), fn (Builder $query) => $query
                ->whereHas('destination', fn (Builder $query) => $query->where('slug', $filters['destination'])))
            ->when(filled($filters['date'] ?? null), fn (Builder $query) => $query
                ->whereDate('start_date', '<=', $filters['date'])
                ->whereDate('end_date', '>=', $filters['date']))
            ->when(filled($filters['min_price'] ?? null), fn (Builder $query) => $query
                ->where('price', '>=', floor($client->basePriceFor($filters['min_price']))))
            ->when(filled($filters['max_price'] ?? null), fn (Builder $query) => $query
                ->where('price', '<=', ceil($client->basePriceFor($filters['max_price']))))
            ->orderBy('sort_order')
            ->orderBy('start_date')
            ->orderByDesc('id')
            ->paginate($request->integer('per_page', 20))
            ->withQueryString();

        return PackageResource::collection($packages);
    }

    public function show(Package $package): PackageResource
    {
        abort_unless($package->is_active, 404);

        return new PackageResource($package->load('service:id,name,slug', 'destination:id,name,slug,image_url'));
    }
}
