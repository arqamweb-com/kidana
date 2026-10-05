<?php

namespace App\Http\Requests;

use App\Models\Hotel;
use App\Models\HotelRoom;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class HotelStayRequest extends FormRequest
{
    public const MAX_NIGHTS = 30;

    public const MAX_ROOMS = 10;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'room_id' => [
                'required',
                'integer',
                Rule::exists('hotel_rooms', 'id')
                    ->where('hotel_id', $this->hotel()->id)
                    ->where('is_active', true),
            ],
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'rooms' => ['required', 'integer', 'min:1', 'max:'.self::MAX_ROOMS],
            'adults' => ['required', 'integer', 'min:1', 'max:99'],
            'children' => ['nullable', 'integer', 'min:0', 'max:99'],
        ];
    }

    /**
     * @return array<int, callable>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $room = $this->room();
                $rooms = (int) $this->input('rooms');
                $adults = (int) $this->input('adults');
                $children = (int) $this->input('children', 0);

                if ($this->nights() > self::MAX_NIGHTS) {
                    $validator->errors()->add('check_out', __('hotels.booking.errors.max_nights', ['count' => self::MAX_NIGHTS]));
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

    /**
     * Invalid stay details on the booking page send the visitor back to the hotel page.
     */
    protected function getRedirectUrl(): string
    {
        if ($this->isMethod('get')) {
            return route('hotels.show', ['hotel' => $this->hotel()->slug]).'#availability';
        }

        return parent::getRedirectUrl();
    }

    public function hotel(): Hotel
    {
        return $this->route('hotel');
    }

    public function room(): HotelRoom
    {
        return once(fn (): HotelRoom => $this->hotel()->rooms()->findOrFail($this->integer('room_id')));
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
            'total' => $room->price_per_night * $this->nights() * $rooms,
        ];
    }
}
