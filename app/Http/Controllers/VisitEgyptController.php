<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Package;
use Illuminate\Contracts\View\View;

class VisitEgyptController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): View
    {
        return view('pages.visit-egypt', [
            'egyptPackages' => Package::query()
                ->with(['destination:id,name,slug', 'service:id,name'])
                ->active()
                ->whereHas('destination', fn ($query) => $query->where('slug', 'egypt'))
                ->orderBy('sort_order')
                ->orderBy('start_date')
                ->orderByDesc('created_at')
                ->get(),
            'egyptFaqs' => Faq::query()
                ->active()
                ->tagged('egypt')
                ->orderBy('sort_order')
                ->get(),
        ]);
    }
}
