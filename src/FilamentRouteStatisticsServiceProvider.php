<?php

namespace Amendozaaguiar\FilamentRouteStatistics;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentRouteStatisticsServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-route-statistics';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasTranslations();
    }
}
