<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\Api\V1\Concerns\AppliesClientMarkup;
use App\Http\Resources\Api\V1\Concerns\ResolvesImageUrls;
use App\Models\HotelRoom;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin HotelRoom
 */
class HotelRoomResource extends JsonResource
{
    use AppliesClientMarkup;
    use ResolvesImageUrls;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'bed_type' => $this->bed_type,
            'size_sqm' => $this->size_sqm,
            'max_adults' => $this->max_adults,
            'max_children' => $this->max_children,
            'price_per_night' => $this->clientPrice($request, $this->price_per_night),
            'currency' => 'EGP',
            'amenities' => $this->amenities ?? [],
            'image_url' => $this->imageUrl($this->image_url),
        ];
    }
}
