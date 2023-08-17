<?php

namespace Amendozaaguiar\FilamentRouteStatistics;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentRouteStatisticsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-route-statistics')
            ->hasConfigFile()
            ->hasTranslations()
            ->hasViews();
    }
}
