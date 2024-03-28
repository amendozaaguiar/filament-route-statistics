<?php

namespace Amendozaaguiar\FilamentRouteStatistics;

use Amendozaaguiar\FilamentRouteStatistics\Widgets\RouteStatisticsOverview;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Livewire\Livewire;

class FilamentRouteStatisticsServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-route-statistics';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasViews()
            ->hasTranslations();
    }

    public function packageBooted(): void
    {
        Livewire::component('route-statistics-overwiew', RouteStatisticsOverview::class);
    }
}
