<?php

namespace App\Http\Requests\Api\V1;

use App\Enum\ApiPaymentMode;
use App\Http\Requests\HotelStayRequest;
use App\Models\ApiClient;
use App\Models\HotelRoom;
use App\Models\Package;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreBookingRequest extends FormRequest
{
    public const TYPE_PACKAGE = 'package';

    public const TYPE_HOTEL_ROOM = 'hotel_room';

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isHotelRoom = Rule::requiredIf(fn (): bool => $this->isHotelRoom());

        return [
            'type' => ['required', Rule::in([self::TYPE_PACKAGE, self::TYPE_HOTEL_ROOM])],
            'external_reference' => ['nullable', 'string', 'max:191'],

            'package' => [
                Rule::requiredIf(fn (): bool => $this->input('type') === self::TYPE_PACKAGE),
                'nullable',
                'string',
                Rule::exists('packages', 'slug')->where('is_active', true),
            ],
            'guests' => ['nullable', 'integer', 'min:1', 'max:99'],
            'travel_date' => ['nullable', 'date', 'after_or_equal:today'],

            'hotel_room_id' => [$isHotelRoom, 'nullable', 'integer', Rule::exists('hotel_rooms', 'id')->where('is_active', true)],
            'check_in' => [$isHotelRoom, 'nullable', 'date', 'after_or_equal:today'],
            'check_out' => [$isHotelRoom, 'nullable', 'date', 'after:check_in'],
            'rooms' => [$isHotelRoom, 'nullable', 'integer', 'min:1', 'max:'.HotelStayRequest::MAX_ROOMS],
            'adults' => [$isHotelRoom, 'nullable', 'integer', 'min:1', 'max:99'],
            'children' => ['nullable', 'integer', 'min:0', 'max:99'],

            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email:rfc', 'max:255'],
            'customer_mobile' => ['required', 'string', $this->paysWithFawry() ? 'regex:/^01[0-9]{9}$/' : 'regex:/^\+?[0-9]{7,15}$/'],
            'message' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * @return array<int, callable>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->isNotEmpty() || ! $this->isHotelRoom()) {
                    return;
                }

                $room = $this->room();
                $rooms = $this->integer('rooms');
                $adults = $this->integer('adults');
                $children = $this->integer('children');

                if (! $room->hotel->is_active) {
                    $validator->errors()->add('hotel_room_id', __('validation.exists', ['attribute' => 'hotel room id']));

                    return;
                }

                if ($this->nights() > HotelStayRequest::MAX_NIGHTS) {
                    $validator->errors()->add('check_out', __('hotels.booking.errors.max_nights', ['count' => HotelStayRequest::MAX_NIGHTS]));
                }

                if ($adults < $rooms) {
                    $validator->errors()->add('adults', __('hotels.booking.errors.adult_per_room'));
                }

                if ($adults > $room->max_adults * $rooms) {
                    $validator->errors()->add('adults', __('hotels.booking.errors.too_many_adults', ['count' => $room->max_adults * $rooms]));
                }

                if ($children > $room->max_children * $rooms) {
                    $validator->errors()->add('children', __('hotels.booking.errors.too_many_children', ['count' => $room->max_children * $rooms]));
                }

                if ($validator->errors()->isEmpty() && $room->availableRoomsBetween($this->checkIn(), $this->checkOut()) < $rooms) {
                    $validator->errors()->add('rooms', __('hotels.booking.errors.not_available'));
                }
            },
        ];
    }

    public function client(): ApiClient
    {
        return $this->user();
    }

    public function paysWithFawry(): bool
    {
        return $this->client()->payment_mode === ApiPaymentMode::Fawry;
    }

    public function isHotelRoom(): bool
    {
        return $this->input('type') === self::TYPE_HOTEL_ROOM;
    }

    public function package(): Package
    {
        return once(fn (): Package => Package::query()->active()->where('slug', $this->string('package'))->firstOrFail());
    }

    public function room(): HotelRoom
    {
        return once(fn (): HotelRoom => HotelRoom::query()->with('hotel')->findOrFail($this->integer('hotel_room_id')));
    }

    public function checkIn(): Carbon
    {
        return Carbon::parse($this->input('check_in'))->startOfDay();
    }

    public function checkOut(): Carbon
    {
        return Carbon::parse($this->input('check_out'))->startOfDay();
    }

    public function nights(): int
    {
        return (int) $this->checkIn()->diffInDays($this->checkOut());
    }

    /**
     * The hotel stay priced for the calling partner, ready for the booking service.
     *
     * @return array{room: HotelRoom, check_in: Carbon, check_out: Carbon, nights: int, rooms: int, adults: int, children: int, total: int}
     */
    public function stay(): array
    {
        $room = $this->room();
        $rooms = $this->integer('rooms');

        return [
            'room' => $room,
            'check_in' => $this->checkIn(),
            'check_out' => $this->checkOut(),
            'nights' => $this->nights(),
            'rooms' => $rooms,
            'adults' => $this->integer('adults'),
            'children' => $this->integer('children'),
            'total' => $this->client()->priceFor($room->price_per_night) * $this->nights() * $rooms,
        ];
    }

    /**
     * @return array{customer_name: string, customer_email: string, customer_mobile: string, guests: ?int, travel_date: ?string, message: ?string}
     */
    public function customer(): array
    {
        return [
            'customer_name' => $this->validated('customer_name'),
            'customer_email' => $this->validated('customer_email'),
            'customer_mobile' => $this->validated('customer_mobile'),
            'guests' => $this->validated('guests'),
            'travel_date' => $this->validated('travel_date'),
            'message' => $this->validated('message'),
        ];
    }
}
