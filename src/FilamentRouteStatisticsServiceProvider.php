<?php

namespace Amendozaaguiar\FilamentRouteStatistics;

use Illuminate\Support\Facades\Gate;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentRouteStatisticsServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-auditing';

    public function configurePackage(Package $package): void
    {
        $package->name('filament-route-statistics')
            ->hasConfigFile('filament-route-statistics')
            ->hasTranslations('filament-route-statistics')
            ->hasViews('filament-route-statistics');
    }

    public function packageBooted(): void
    {
        parent::packageBooted();

        // Default values for view and restore audits. Add a policy to override these values
        Gate::define('route-statictics', function ($user, $resource) {
            return true;
        });
    }
}
