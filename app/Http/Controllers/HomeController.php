<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Faq;
use App\Models\Hotel;
use App\Models\Office;
use App\Models\Package;
use App\Models\Partner;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $locale = app()->getLocale();

        return view('pages.home', [
            'destinations' => Destination::query()
                ->active()
                ->orderBy('sort_order')
                ->orderBy("name->{$locale}")
                ->get(['id', 'name', 'slug', 'image_url']),
            'services' => Service::query()
                ->active()
                ->orderBy('sort_order')
                ->orderBy("name->{$locale}")
                ->limit(4)
                ->get(),
            'packages' => Package::query()
                ->with(['destination:id,name,slug', 'service:id,name'])
                ->active()
                ->orderBy('sort_order')
                ->orderBy("name->{$locale}")
                ->limit(6)
                ->get(),
            'hotels' => Hotel::query()
                ->with('destination:id,name,slug')
                ->withMin(['rooms as starting_price' => fn (Builder $query) => $query->active()], 'price_per_night')
                ->active()
                ->orderBy('sort_order')
                ->orderBy("name->{$locale}")
                ->limit(3)
                ->get(),
            'homeFaqs' => Faq::query()
                ->active()
                ->tagged('home')
                ->orderBy('sort_order')
                ->orderBy("title->{$locale}")
                ->get(),
            'homeTestimonials' => Testimonial::query()
                ->active()
                ->tagged('home')
                ->orderBy('sort_order')
                ->orderBy("name->{$locale}")
                ->get(),
            'offices' => Office::query()
                ->active()
                ->orderBy('sort_order')
                ->orderBy("name->{$locale}")
                ->get(),
            'partners' => Partner::query()
                ->active()
                ->orderBy('sort_order')
                ->orderBy("name->{$locale}")
                ->get(),
        ]);
    }
}
