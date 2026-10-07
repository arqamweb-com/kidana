<?php

namespace App\Providers;

use App\Models\Destination;
use App\Models\Service;
use App\Services\FawryService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(FawryService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        RateLimiter::for('partner-api', fn (Request $request): Limit => Limit::perMinute(config('partner_api.rate_limit_per_minute'))
            ->by('api-client:'.($request->user()?->getAuthIdentifier() ?? $request->ip())));

        View::composer('layout.navbar', function ($view): void {
            if (! Schema::hasTable('services')) {
                $view->with('navigationServices', collect());

                return;
            }

            $locale = app()->getLocale();

            $view->with('navigationServices', Service::query()
                ->active()
                ->orderBy('sort_order')
                ->orderBy("name->{$locale}")
                ->get(['id', 'name', 'slug']));
        });

        View::composer('layout.footer', function ($view): void {
            if (! Schema::hasTable('services') || ! Schema::hasTable('destinations')) {
                $view->with('footerServices', collect());
                $view->with('footerDestinations', collect());

                return;
            }

            $locale = app()->getLocale();

            $view->with('footerServices', Service::query()
                ->active()
                ->orderBy('sort_order')
                ->limit(5)
                ->get(['id', 'name', 'slug']));

            $view->with('footerDestinations', Destination::query()
                ->active()
                ->orderBy('sort_order')
                ->limit(5)
                ->get(['id', 'name', 'slug']));
        });
    }
}
