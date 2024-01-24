<?php

namespace Amendozaaguiar\FilamentRouteStatistics;

use Amendozaaguiar\FilamentRouteStatistics\Resources\RouteStatisticsResource;
use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentRouteStatisticsPlugin implements Plugin
{

    public static function make(): static
    {
        return app(static::class);
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources(config('filament-route-statistics.resource.classes'));
    }

    public function getId(): string
    {
        return 'filament-route-statistics';
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
