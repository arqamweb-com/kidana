<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Contracts\View\View;

class PackageShowController extends Controller
{
    public function __invoke(string $locale, Package $package): View
    {
        abort_unless($package->is_active, 404);

        $package->loadMissing([
            'destination:id,name,slug',
            'service:id,name,slug',
            'faqs' => fn ($query) => $query->active()->orderBy('sort_order'),
            'testimonials' => fn ($query) => $query->active()->orderBy('sort_order'),
        ]);

        return view('packages.show', [
            'package' => $package,
        ]);
    }
}
