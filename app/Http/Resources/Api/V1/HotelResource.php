<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\Api\V1\Concerns\ResolvesImageUrls;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Hotel
 */
class HotelResource extends JsonResource
{
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
            'address' => $this->address,
            'stars' => $this->stars,
            'board_basis' => $this->board_basis?->value,
            'check_in_time' => $this->check_in_time,
            'check_out_time' => $this->check_out_time,
            'cancellation_policy' => $this->cancellation_policy,
            'amenities' => $this->amenities,
            'image_url' => $this->imageUrl($this->image_url),
            'gallery' => $this->imageUrls($this->gallery),
            'destination' => new DestinationResource($this->whenLoaded('destination')),
            'rooms' => HotelRoomResource::collection($this->whenLoaded('rooms')),
        ];
    }
}
