<?php

namespace App\Filament\Widgets;

use App\Models\Faq;
use App\Models\Package;
use App\Models\Service;
use App\Models\Testimonial;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class ContentReadinessWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected static bool $isLazy = false;

    protected ?string $heading = 'Content readiness';

    protected ?string $pollingInterval = null;

    protected function getColumns(): int|array|null
    {
        return 4;
    }

    protected function getStats(): array
    {
        return [
            $this->activePackages(),
            $this->packageReadiness(),
            $this->liveServices(),
            $this->homepageContent(),
        ];
    }

    private function activePackages(): Stat
    {
        $active = Package::query()->active()->count();
        $total = Package::query()->count();

        return Stat::make('Active packages', Number::format($active))
            ->description("{$active}/{$total} published")
            ->descriptionIcon(Heroicon::OutlinedRectangleStack)
            ->color($active > 0 ? 'success' : 'gray');
    }

    private function packageReadiness(): Stat
    {
        $active = Package::query()->active()->count();

        $ready = Package::query()
            ->active()
            ->has('faqs')
            ->has('testimonials')
            ->count();

        $percentage = $active > 0 ? (int) round(($ready / $active) * 100) : 0;

        return Stat::make('Package readiness', "{$percentage}%")
            ->description("{$ready}/{$active} FAQ + reviews")
            ->descriptionIcon($percentage >= 100 ? Heroicon::OutlinedCheckCircle : Heroicon::OutlinedExclamationTriangle)
            ->color($percentage >= 100 ? 'success' : 'warning');
    }

    private function liveServices(): Stat
    {
        $count = Service::query()->active()->count();

        return Stat::make('Live services', Number::format($count))
            ->description(Number::format(Service::query()->count()).' total services')
            ->descriptionIcon(Heroicon::OutlinedSquares2x2)
            ->color($count > 0 ? 'success' : 'gray');
    }

    private function homepageContent(): Stat
    {
        $testimonials = Testimonial::query()->active()->tagged('home')->count();
        $faqs = Faq::query()->active()->tagged('home')->count();
        $total = $testimonials + $faqs;

        return Stat::make('Homepage content', Number::format($total))
            ->description("{$testimonials} testimonials · {$faqs} FAQs tagged home")
            ->descriptionIcon(Heroicon::OutlinedSparkles)
            ->color($total > 0 ? 'primary' : 'gray');
    }
}
