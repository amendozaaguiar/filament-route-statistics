<?php

namespace Amendozaaguiar\FilamentRouteStatistics\Resources\RouteStatisticsResource\Pages;

use Amendozaaguiar\FilamentRouteStatistics\Resources\RouteStatisticsResource;
use Amendozaaguiar\FilamentRouteStatistics\Resources\RouteStatisticsResource\Widgets\RouteStatisticsOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRouteStatistics extends ListRecords
{
    protected static string $resource = RouteStatisticsResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            RouteStatisticsOverview::class,
        ];
    }
}
