<?php

namespace App\Providers;

use App\Models\Service;
use App\Services\FawryService;
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
    }
}
