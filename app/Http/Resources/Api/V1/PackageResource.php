<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\Api\V1\Concerns\AppliesClientMarkup;
use App\Http\Resources\Api\V1\Concerns\ResolvesImageUrls;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Package
 */
class PackageResource extends JsonResource
{
    use AppliesClientMarkup;
    use ResolvesImageUrls;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->slug,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->clientPrice($request, $this->price),
            'currency' => 'EGP',
            'location' => $this->location_label,
            'start_date' => $this->start_date?->toDateString(),
            'end_date' => $this->end_date?->toDateString(),
            'days' => $this->days,
            'max_guests' => $this->max_guests,
            'image_url' => $this->imageUrl($this->image_url),
            'service' => $this->whenLoaded('service', fn () => $this->service?->only('slug', 'name')),
            'destination' => new DestinationResource($this->whenLoaded('destination')),
            $this->mergeWhen($request->route('package') !== null, fn (): array => [
                'highlights' => $this->highlights,
                'itinerary' => $this->itinerary,
                'included_items' => $this->included_items,
                'excluded_items' => $this->excluded_items,
                'gallery' => $this->imageUrls($this->gallery),
            ]),
        ];
    }
}
