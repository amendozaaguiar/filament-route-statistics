<?php

namespace Amendozaaguiar\FilamentRouteStatistics;

use Amendozaaguiar\FilamentRouteStatistics\Pages\RouteStatistics;
use Amendozaaguiar\FilamentRouteStatistics\Widgets\RouteStatisticsOverview;
use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentRouteStatisticsPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-route-statistics';
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public function register(Panel $panel): void
    {
        $panel
            ->pages([
                RouteStatistics::class
            ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
