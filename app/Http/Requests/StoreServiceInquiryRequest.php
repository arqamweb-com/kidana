<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreServiceInquiryRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'travel_date' => ['nullable', 'date', 'after_or_equal:today'],
            'people' => ['nullable', 'integer', 'min:1', 'max:999'],
            'message' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
