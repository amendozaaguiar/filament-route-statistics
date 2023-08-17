<?php

namespace Amendozaaguiar\Amendozaaguiar\FilamentRouteStatistics\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use OwenIt\Auditing\Contracts\Audit;

class FilamentRouteStatisticsRelationManager extends RelationManager
{
    protected static string $relationship = 'route_statistics';

    protected static ?string $recordTitleAttribute = 'id';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return auth()->user()->can('audit', $ownerRecord);
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return trans('filament-route-statistics::filament-route-statistics.table.heading');
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with('user')->orderBy(config('filament-route-statistics.audits_sort.column'), config('filament-route-statistics.audits_sort.direction')))
            ->columns(Arr::flatten([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(trans('filament-route-statistics::filament-route-statistics.column.user_name')),
                Tables\Columns\TextColumn::make('event')
                    ->label(trans('filament-route-statistics::filament-route-statistics.column.event')),
                Tables\Columns\TextColumn::make('created_at')
                    ->since()
                    ->label(trans('filament-route-statistics::filament-route-statistics.column.created_at')),
                Tables\Columns\ViewColumn::make('old_values')
                    ->view('filament-route-statistics::tables.columns.key-value')
                    ->label(trans('filament-route-statistics::filament-route-statistics.column.old_values')),
                Tables\Columns\ViewColumn::make('new_values')
                    ->view('filament-route-statistics::tables.columns.key-value')
                    ->label(trans('filament-route-statistics::filament-route-statistics.column.new_values')),
                self::extraColumns(),
            ]))
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ]);
    }

    protected static function extraColumns()
    {
        return Arr::map(config('filament-route-statistics.audits_extend'), function ($buildParameters, $columnName) {
            return collect($buildParameters)->pipeThrough([
                function ($collection) use ($columnName) {
                    $columnClass = (string) $collection->get('class');

                    if (!is_null($collection->get('methods'))) {
                        $columnClass = $columnClass::make($columnName);

                        collect($collection->get('methods'))->transform(function ($value, $key) use ($columnClass) {
                            if (is_numeric($key)) {
                                return $columnClass->$value();
                            }

                            return $columnClass->$key($value);
                        });

                        return $columnClass;
                    }

                    return $columnClass::make($columnName);
                },
            ]);
        });
    }


    protected function canCreate(): bool
    {
        return false;
    }

    protected function canEdit(Model $record): bool
    {
        return false;
    }

    protected function canDelete(Model $record): bool
    {
        return false;
    }
}
