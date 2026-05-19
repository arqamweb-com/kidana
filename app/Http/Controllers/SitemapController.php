<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Package;
use App\Models\Service;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        $locales = array_keys(config('locales.supported'));
        $urls = collect();

        foreach ($locales as $locale) {
            $urls->push(route('home', ['locale' => $locale]));
            $urls->push(route('packages', ['locale' => $locale]));
            $urls->push(route('services.index', ['locale' => $locale]));
            $urls->push(route('destinations.index', ['locale' => $locale]));
            $urls->push(route('about', ['locale' => $locale]));
            $urls->push(route('contact', ['locale' => $locale]));
            $urls->push(route('umrah-plus', ['locale' => $locale]));
        }

        Package::query()
            ->active()
            ->get(['slug'])
            ->each(function (Package $package) use ($locales, $urls): void {
                foreach ($locales as $locale) {
                    $urls->push(route('packages.show', ['locale' => $locale, 'package' => $package->slug]));
                }
            });

        Service::query()
            ->active()
            ->get(['slug'])
            ->each(function (Service $service) use ($locales, $urls): void {
                foreach ($locales as $locale) {
                    $urls->push(route('services.show', ['locale' => $locale, 'service' => $service->slug]));
                }
            });

        Destination::query()
            ->active()
            ->get(['slug'])
            ->each(function (Destination $destination) use ($locales, $urls): void {
                foreach ($locales as $locale) {
                    $urls->push(route('destinations.show', ['locale' => $locale, 'destination' => $destination->slug]));
                }
            });

        return response()
            ->view('sitemap', ['urls' => $urls->unique()->values()])
            ->header('Content-Type', 'application/xml');
    }
}
