<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;

class StoreHotelBookingRequest extends HotelStayRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email:rfc', 'max:255'],
            'customer_mobile' => ['required', 'string', 'regex:/^01[0-9]{9}$/'],
            'message' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
