<?php

namespace Amendozaaguiar\FilamentRouteStatistics;

use Amendozaaguiar\FilamentRouteStatistics\Resources\RouteStatisticsResource;
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
            ->resources([
                RouteStatisticsResource::class,
            ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
