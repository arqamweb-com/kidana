<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Contracts\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        return view('pages.services', [
            'services' => Service::query()
                ->active()
                ->orderBy('sort_order')
                ->orderBy('name->' . app()->getLocale())
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
                ->orderBy('name->' . app()->getLocale())
                ->limit(4)
                ->get(),
        ]);
    }
}
