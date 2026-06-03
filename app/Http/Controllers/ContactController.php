<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Mail\ContactMessageReceived;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('pages.contact');
    }

    public function store(StoreContactRequest $request, string $locale): RedirectResponse
    {
        $data = $request->validated();

        Mail::to(config('mail.contact_address', config('mail.from.address')))
            ->queue(new ContactMessageReceived(
                senderName: $data['name'],
                senderEmail: $data['email'],
                senderPhone: $data['phone'] ?? null,
                body: $data['message'],
            ));

        return redirect()
            ->route('contact', ['locale' => $locale])
            ->with('success', __('contact.form.success'));
    }
}
