<?php

namespace App\Http\Resources\Api\V1\Concerns;

use App\Models\ApiClient;
use Illuminate\Http\Request;

trait AppliesClientMarkup
{
    /**
     * The price as the calling partner sees it, with its markup applied.
     */
    protected function clientPrice(Request $request, int|float|string|null $basePrice): ?int
    {
        if ($basePrice === null) {
            return null;
        }

        $client = $request->user();

        return $client instanceof ApiClient ? $client->priceFor($basePrice) : (int) round((float) $basePrice);
    }
}
