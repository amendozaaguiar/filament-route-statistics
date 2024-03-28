<?php

namespace Amendozaaguiar\FilamentRouteStatistics\Resources;

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
use Illuminate\Support\Stringable;

class RouteStatisticsResource extends Resource
{
    protected static ?string $model = RouteStatistic::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static ?string $navigationGroup = 'Estadísticas';

    protected static ?string $modelLabel = 'Estadística de ruta';

    protected static ?string $pluralModelLabel = 'Estadísticas de rutas';

    protected static string $userName = 'email';

    protected static ?string $teamModel = null;

    protected static ?string $teamColumn = 'team_id';

    protected static string $dateFormat = 'M j, Y H:i:s';

    protected static string $sortColumn = 'id';

    protected static string $sortDirection = 'desc';

    public static function getUserName(): string
    {
        return static::$userName;
    }

    public static function getTeamModel(): ?string
    {
        return static::$teamModel;
    }

    public static function getTeamColumn(): ?string
    {
        return static::$teamColumn;
    }

    public static function getDateFormat(): string
    {
        return static::$dateFormat;
    }

    public static function getSortColumn(): string
    {
        return static::$sortColumn;
    }

    public static function getSortDirection(): string
    {
        return static::$sortDirection;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('filament-route-statistics::filament-route-statistics.table.columns.id')),

                TextColumn::make('user.' . static::getUserName())
                    ->label(__('filament-route-statistics::filament-route-statistics.table.columns.user.name')),

                TextColumn::make(static::getTeamModel() ? 'team.' . static::getTeamColumn() : static::getTeamColumn())
                    ->label(__('filament-route-statistics::filament-route-statistics.table.columns.team'))
                    ->visible(static::getTeamModel() ? true : false),

                TextColumn::make('method')
                    ->label(__('filament-route-statistics::filament-route-statistics.table.columns.method'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'GET' => 'success',
                        'POST' => 'warning',
                        'PUT' => 'info',
                        'PATCH' => 'gray',
                        'DELETE' => 'danger',
                        default => 'gray'
                    }),

                TextColumn::make('route')
                    ->label(__('filament-route-statistics::filament-route-statistics.table.columns.route')),

                TextColumn::make('status')
                    ->label(__('filament-route-statistics::filament-route-statistics.table.columns.status'))
                    ->badge()
                    ->color(fn (string $state): string => match (substr($state, 0, 1)) {
                        '1' => 'gray',
                        '2' => 'success',
                        '3' => 'info',
                        '4' => 'warning',
                        '5' => 'danger',
                        default => 'gray'
                    }),

                TextColumn::make('ip')
                    ->label(__('filament-route-statistics::filament-route-statistics.table.columns.ip')),

                TextColumn::make('date')
                    ->label(__('filament-route-statistics::filament-route-statistics.table.columns.date'))
                    ->dateTime(static::getDateFormat()),

                TextColumn::make('counter')
                    ->label(__('filament-route-statistics::filament-route-statistics.table.columns.counter'))
                    ->numeric(),
            ])
            ->filters([
                // SelectFilter::make('user_id')
                //     ->label(__('filament-route-statistics::filament-route-statistics.table.columns.user'))
                //     ->multiple()
                //     ->options(fn () => User::select('id', config('filament-route-statistics.username.column'))->pluck(config('filament-route-statistics.username.column'), 'id')->toArray())
                //     ->attribute('user_id'),

                // SelectFilter::make('team_id')
                //     ->label(__('filament-route-statistics::filament-route-statistics.table.columns.team'))
                //     ->multiple()
                //     ->options(function () {
                //         if (config('filament-route-statistics.team.model')) {
                //             return config('filament-route-statistics.team.model')::select('id', config('filament-route-statistics.team.column'))->pluck(config('filament-route-statistics.team.column'), 'id')->toArray();
                //         }

                //         return [];
                //     })
                //     ->visible(config('filament-route-statistics.team.model') ? true : false)
                //     ->attribute('team_id'),

                // SelectFilter::make('method')
                //     ->label(__('filament-route-statistics::filament-route-statistics.table.columns.method'))
                //     ->multiple()
                //     ->options(fn () => RouteStatistic::select('method')->distinct()->pluck('method', 'method')->toArray())
                //     ->attribute('method'),

                // SelectFilter::make('status')
                //     ->label(__('filament-route-statistics::filament-route-statistics.table.columns.status'))
                //     ->multiple()
                //     ->options(fn () => RouteStatistic::select('status')->distinct()->pluck('status', 'status')->toArray())
                //     ->attribute('status'),

                // SelectFilter::make('route')
                //     ->label(__('filament-route-statistics::filament-route-statistics.table.columns.route'))
                //     ->multiple()
                //     ->options(fn () => RouteStatistic::select('route')->distinct()->pluck('route', 'route')->toArray())
                //     ->attribute('route'),

                // Filter::make('date')
                //     ->form([
                //         DatePicker::make('created_from'),
                //         DatePicker::make('created_until'),
                //     ])
                //     ->query(function (Builder $query, array $data): Builder {
                //         return $query
                //             ->when(
                //                 $data['created_from'],
                //                 fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                //             )
                //             ->when(
                //                 $data['created_until'],
                //                 fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                //             );
                //     })
            ])
            ->defaultSort(static::getSortColumn(), static::getSortDirection());
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRouteStatistics::route('/'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            RouteStatisticsOverview::class,
        ];
    }
}
