<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\HotelStayRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Validator;

class HotelAvailabilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'adults' => ['nullable', 'integer', 'min:1', 'max:99'],
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
                if ($validator->errors()->isEmpty() && $this->nights() > HotelStayRequest::MAX_NIGHTS) {
                    $validator->errors()->add('check_out', __('hotels.booking.errors.max_nights', ['count' => HotelStayRequest::MAX_NIGHTS]));
                }
            },
        ];
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
}
