<?php

namespace Amendozaaguiar\FilamentRouteStatistics\Resources\RouteStatisticsResource\Pages;

use Amendozaaguiar\FilamentRouteStatistics\Resources\RouteStatisticsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRouteStatistics extends ListRecords
{
    protected static string $resource = RouteStatisticsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
