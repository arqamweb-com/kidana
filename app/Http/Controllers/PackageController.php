<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Contracts\View\View;

class PackageController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.packages', [
            'packages' => Package::query()
                ->with(['destination:id,name,slug', 'service:id,name'])
                ->active()
                ->orderBy('sort_order')
                ->orderBy('start_date')
                ->orderByDesc('created_at')
                ->paginate(9),
        ]);
    }
}
