<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceInquiryRequest;
use App\Mail\ServiceInquiryReceived;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class ServiceController extends Controller
{
    public function index(): View
    {
        return view('pages.services', [
            'services' => Service::query()
                ->active()
                ->orderBy('sort_order')
                ->orderBy('name->'.app()->getLocale())
                ->get(),
        ]);
    }

    public function show(string $locale, Service $service): View
    {
        abort_unless($service->is_active, 404);

        $service->loadMissing([
            'faqs' => fn ($query) => $query->active()->orderBy('sort_order'),
            'testimonials' => fn ($query) => $query->active()->orderBy('sort_order'),
        ]);

        return view('services.show', [
            'inquirySent' => session('inquiry_sent'),
            'service' => $service,
            'servicePackages' => $service->packages()
                ->with('destination:id,name,slug')
                ->active()
                ->orderBy('sort_order')
                ->orderBy('start_date')
                ->orderByDesc('created_at')
                ->get(),
            'relatedServices' => Service::query()
                ->active()
                ->whereKeyNot($service->getKey())
                ->orderBy('sort_order')
                ->orderBy('name->'.app()->getLocale())
                ->limit(4)
                ->get(),
        ]);
    }

    public function inquiry(StoreServiceInquiryRequest $request, string $locale, Service $service): RedirectResponse
    {
        abort_unless($service->is_active, 404);

        $data = $request->validated();

        Mail::to(config('mail.contact_address', config('mail.from.address')))
            ->send(new ServiceInquiryReceived(
                serviceName: $service->getTranslation('name', $locale),
                senderName: $data['name'],
                senderEmail: $data['email'],
                senderPhone: $data['phone'],
                travelDate: $data['travel_date'] ?? null,
                people: isset($data['people']) ? (int) $data['people'] : null,
                body: $data['message'] ?? null,
            ));

        return redirect()
            ->route('services.show', ['locale' => $locale, 'service' => $service->slug])
            ->with('inquiry_sent', true);
    }
}
