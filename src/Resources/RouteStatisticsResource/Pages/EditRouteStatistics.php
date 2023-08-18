<?php

namespace Amendozaaguiar\FilamentRouteStatistics\Resources\RouteStatisticsResource\Pages;

use Amendozaaguiar\FilamentRouteStatistics\Resources\RouteStatisticsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRouteStatistics extends EditRecord
{
    protected static string $resource = RouteStatisticsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
