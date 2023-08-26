<?php

namespace Amendozaaguiar\FilamentRouteStatistics\Resources;

use Amendozaaguiar\FilamentRouteStatistics\Resources\RouteStatisticsResource\Pages\CreateRouteStatistics;
use Amendozaaguiar\FilamentRouteStatistics\Resources\RouteStatisticsResource\Pages\EditRouteStatistics;
use Amendozaaguiar\FilamentRouteStatistics\Resources\RouteStatisticsResource\Pages\ListRouteStatistics;
use Amendozaaguiar\FilamentRouteStatistics\Resources\RouteStatisticsResource\Widgets\RouteStatisticsOverview;
use Bilfeldt\LaravelRouteStatistics\Models\RouteStatistic;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User;

use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class RouteStatisticsResource extends Resource
{
    protected static ?string $model = RouteStatistic::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';

    public static function getBreadcrumb(): string
    {
        return '';
    }


    public static function getLabel(): string
    {
        return __('filament-route-statistics::filament-route-statistics.navigation.label');
    }

    public static function getPluralLabel(): string
    {
        return __('filament-route-statistics::filament-route-statistics.navigation.pluralLabel');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament-route-statistics::filament-route-statistics.navigation.group');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(trans('filament-route-statistics::filament-route-statistics.table.columns.id'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label(trans('filament-route-statistics::filament-route-statistics.table.columns.user.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('method')
                    ->label(trans('filament-route-statistics::filament-route-statistics.table.columns.method'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'GET' => 'success',
                        'POST' => 'warning',
                        'PUT' => 'info',
                        'PATCH' => 'gray',
                        'DELETE' => 'danger',
                        default => 'gray'
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('route')
                    ->label(trans('filament-route-statistics::filament-route-statistics.table.columns.route'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(trans('filament-route-statistics::filament-route-statistics.table.columns.status'))
                    ->badge()
                    ->color(fn (string $state): string => match (substr($state, 0, 1)) {
                        '1' => 'gray',
                        '2' => 'success',
                        '3' => 'info',
                        '4' => 'warning',
                        '5' => 'danger',
                        default => 'gray'
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('ip')
                    ->label(trans('filament-route-statistics::filament-route-statistics.table.columns.ip'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date')
                    ->label(trans('filament-route-statistics::filament-route-statistics.table.columns.date'))
                    ->dateTime()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('counter')
                    ->label(trans('filament-route-statistics::filament-route-statistics.table.columns.counter'))
                    ->numeric()
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('user_id')
                    ->label(trans('filament-route-statistics::filament-route-statistics.table.columns.user'))
                    ->multiple()
                    ->options(fn () => User::select('id', 'name')->pluck('name', 'id')->toArray())
                    ->attribute('user_id'),

                SelectFilter::make('method')
                    ->label(trans('filament-route-statistics::filament-route-statistics.table.columns.method'))
                    ->multiple()
                    ->options(fn () => RouteStatistic::select('method')->distinct()->pluck('method', 'method')->toArray())
                    ->attribute('method'),

                SelectFilter::make('status')
                    ->label(trans('filament-route-statistics::filament-route-statistics.table.columns.status'))
                    ->multiple()
                    ->options(fn () => RouteStatistic::select('status')->distinct()->pluck('status', 'status')->toArray())
                    ->attribute('status'),

                SelectFilter::make('status')
                    ->label(trans('filament-route-statistics::filament-route-statistics.table.columns.route'))
                    ->multiple()
                    ->options(fn () => RouteStatistic::select('route')->distinct()->pluck('route', 'route')->toArray())
                    ->attribute('route'),

                Filter::make('date')
                    ->form([
                        DatePicker::make('created_from')->label('Desde'),
                        DatePicker::make('created_until')->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    })
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                ExportAction::make()->exports([
                    ExcelExport::make()
                        ->fromTable()
                ])
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
                ExportBulkAction::make()
            ])
            ->emptyStateActions([
                // Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRouteStatistics::route('/'),
            //'create' => CreateRouteStatistics::route('/create'),
            //'edit' => EditRouteStatistics::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            RouteStatisticsOverview::class,
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }
}
