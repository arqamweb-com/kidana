<?php

namespace App\Filament\Widgets;

use App\Models\Destination;
use App\Models\Faq;
use App\Models\Package;
use App\Models\Service;
use App\Models\Testimonial;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class TravelOperationsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = -10;

    protected static bool $isLazy = false;

    protected ?string $heading = 'Travel operations overview';

    protected ?string $description = 'Live content, package, and homepage readiness metrics.';

    protected ?string $pollingInterval = null;

    protected function getColumns(): int|array|null
    {
        return [
            'md' => 2,
            'xl' => 4,
        ];
    }

    protected function getStats(): array
    {
        $activePackages = Package::query()->active()->count();
        $totalPackages = Package::query()->count();
        $linkedPackages = Package::query()
            ->active()
            ->has('faqs')
            ->has('testimonials')
            ->count();
        $upcomingPackages = Package::query()
            ->active()
            ->whereDate('start_date', '>=', now())
            ->count();
        $homeTestimonials = Testimonial::query()->active()->tagged('home')->count();
        $homeFaqs = Faq::query()->active()->tagged('home')->count();
        $coverage = $this->percentage($linkedPackages, $activePackages);

        return [
            Stat::make('Active packages', Number::format($activePackages))
                ->description($this->ratioLabel($activePackages, $totalPackages, 'published'))
                ->descriptionIcon(Heroicon::OutlinedBriefcase)
                ->chart($this->packagePublicationChart($activePackages, $totalPackages))
                ->color($activePackages > 0 ? 'success' : 'gray'),

            Stat::make('Package readiness', "{$coverage}%")
                ->description($this->ratioLabel($linkedPackages, $activePackages, 'with FAQ + testimonials'))
                ->descriptionIcon($coverage >= 70 ? Heroicon::OutlinedCheckCircle : Heroicon::OutlinedExclamationTriangle)
                ->chart([$coverage, max(0, 100 - $coverage)])
                ->color($coverage >= 70 ? 'success' : 'warning'),

            Stat::make('Upcoming departures', Number::format($upcomingPackages))
                ->description('active packages with future start dates')
                ->descriptionIcon(Heroicon::OutlinedCalendarDays)
                ->chart($this->upcomingPackagesChart())
                ->color($upcomingPackages > 0 ? 'info' : 'gray'),

            Stat::make('Homepage proof', Number::format($homeTestimonials + $homeFaqs))
                ->description("{$homeTestimonials} testimonials / {$homeFaqs} FAQs tagged home")
                ->descriptionIcon(Heroicon::OutlinedSparkles)
                ->chart([$homeTestimonials, $homeFaqs, $homeTestimonials + $homeFaqs])
                ->color(($homeTestimonials + $homeFaqs) > 0 ? 'primary' : 'gray'),

            Stat::make('Live services', Number::format(Service::query()->active()->count()))
                ->description(Number::format(Service::query()->count()) . ' total services')
                ->descriptionIcon(Heroicon::OutlinedSquares2x2)
                ->color('success'),

            Stat::make('Destinations', Number::format(Destination::query()->active()->count()))
                ->description('searchable travel endpoints')
                ->descriptionIcon(Heroicon::OutlinedMapPin)
                ->color('info'),

            Stat::make('Active FAQs', Number::format(Faq::query()->active()->count()))
                ->description(Number::format(Faq::query()->tagged('home')->count()) . ' tagged for home')
                ->descriptionIcon(Heroicon::OutlinedQuestionMarkCircle)
                ->color('warning'),

            Stat::make('Active testimonials', Number::format(Testimonial::query()->active()->count()))
                ->description(Number::format(Testimonial::query()->tagged('home')->count()) . ' tagged for home')
                ->descriptionIcon(Heroicon::OutlinedChatBubbleLeftRight)
                ->color('primary'),
        ];
    }

    private function percentage(int $value, int $total): int
    {
        if ($total === 0) {
            return 0;
        }

        return (int) round(($value / $total) * 100);
    }

    private function ratioLabel(int $value, int $total, string $suffix): string
    {
        return Number::format($value) . ' / ' . Number::format($total) . ' ' . $suffix;
    }

    /**
     * @return array<int, int>
     */
    private function packagePublicationChart(int $activePackages, int $totalPackages): array
    {
        return [
            max(0, $totalPackages - $activePackages),
            $activePackages,
            $totalPackages,
        ];
    }

    /**
     * @return array<int, int>
     */
    private function upcomingPackagesChart(): array
    {
        return collect(range(0, 5))
            ->map(fn (int $monthOffset): int => Package::query()
                ->active()
                ->whereBetween('start_date', [
                    now()->startOfMonth()->addMonths($monthOffset),
                    now()->startOfMonth()->addMonths($monthOffset)->endOfMonth(),
                ])
                ->count())
            ->all();
    }
}
