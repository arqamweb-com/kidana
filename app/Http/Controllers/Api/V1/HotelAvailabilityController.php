<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\HotelAvailabilityRequest;
use App\Http\Resources\Api\V1\HotelRoomResource;
use App\Models\ApiClient;
use App\Models\Hotel;
use App\Models\HotelRoom;
use Illuminate\Http\JsonResponse;

class HotelAvailabilityController extends Controller
{
    public function __invoke(HotelAvailabilityRequest $request, Hotel $hotel): JsonResponse
    {
        abort_unless($hotel->is_active, 404);

        /** @var ApiClient $client */
        $client = $request->user();
        $nights = $request->nights();
        $adults = $request->integer('adults', 1);
        $children = $request->integer('children');

        $rooms = $hotel->rooms()
            ->active()
            ->orderBy('sort_order')
            ->get()
            ->map(function (HotelRoom $room) use ($request, $client, $nights, $adults, $children): array {
                $availableRooms = $room->availableRoomsBetween($request->checkIn(), $request->checkOut());
                $roomsNeeded = $this->roomsNeeded($room, $adults, $children);

                return [
                    ...(new HotelRoomResource($room))->resolve($request),
                    'available_rooms' => $availableRooms,
                    'rooms_needed' => $roomsNeeded,
                    'is_available' => $roomsNeeded !== null && $availableRooms >= $roomsNeeded,
                    'total_for_stay' => $roomsNeeded === null
                        ? null
                        : $client->priceFor($room->price_per_night) * $nights * $roomsNeeded,
                ];
            });

        return response()->json([
            'data' => [
                'hotel' => $hotel->slug,
                'check_in' => $request->checkIn()->toDateString(),
                'check_out' => $request->checkOut()->toDateString(),
                'nights' => $nights,
                'adults' => $adults,
                'children' => $children,
                'currency' => 'EGP',
                'rooms' => $rooms->values(),
            ],
        ]);
    }

    /**
     * The fewest rooms of this type that fit the guests, or null when the room cannot host them.
     */
    private function roomsNeeded(HotelRoom $room, int $adults, int $children): ?int
    {
        if ($room->max_adults < 1 || ($children > 0 && $room->max_children < 1)) {
            return null;
        }

        return (int) max(
            1,
            ceil($adults / $room->max_adults),
            $children > 0 ? ceil($children / $room->max_children) : 1,
        );
    }
}
