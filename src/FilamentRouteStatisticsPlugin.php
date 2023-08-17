<?php

namespace Amendozaaguiar\FilamentRouteStatistics;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Amendozaaguiar\FilamentRouteStatistics\Pages\RouteStatistics;

class FilamentRouteStatisticsPlugin implements Plugin
{
    protected string $page = RouteStatistics::class;

    public function register(Panel $panel): void
    {
        $panel->pages([$this->getPage()]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public function getId(): string
    {
        return 'filament-route-statistics';
    }

    public static function make(): static
    {
        return new static();
    }

    public function usingPage(string $page): static
    {
        $this->page = $page;

        return $this;
    }

    public function getPage(): string
    {
        return $this->page;
    }
}
