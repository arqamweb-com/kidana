<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomPackageRequest;
use App\Mail\CustomPackageRequested;
use App\Models\CustomPackageRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class CustomPackageController extends Controller
{
    public function store(StoreCustomPackageRequest $request, string $locale): RedirectResponse
    {
        $data = $request->validated();

        CustomPackageRequest::create([
            ...$data,
            'locale' => $locale,
        ]);

        Mail::to(config('mail.contact_address', config('mail.from.address')))
            ->send(new CustomPackageRequested(
                senderName: $data['name'],
                senderEmail: $data['email'],
                senderPhone: $data['phone'] ?? null,
                details: [
                    'Destination' => $data['destination'] ?? null,
                    'Travel Type' => $data['travel_type'] ?? null,
                    'Travelers' => $data['travelers'] ?? null,
                    'Travel Date' => $data['travel_date'] ?? null,
                    'Budget' => $data['budget'] ?? null,
                    'Accommodation' => $data['accommodation'] ?? null,
                    'Duration' => $data['duration'] ?? null,
                    'Notes' => $data['notes'] ?? null,
                ],
            ));

        return redirect()
            ->route('book-now', ['locale' => $locale])
            ->with('custom_package_success', __('contact.form.success'));
    }
}
