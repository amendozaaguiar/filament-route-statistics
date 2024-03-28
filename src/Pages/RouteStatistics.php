<?php

namespace Amendozaaguiar\FilamentRouteStatistics\Pages;

use Amendozaaguiar\FilamentRouteStatistics\Widgets\RouteStatisticsOverview;
use Bilfeldt\LaravelRouteStatistics\Models\RouteStatistic;
use Filament\Forms\Components\DateTimePicker;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RouteStatistics extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static string $view = 'filament-route-statistics::pages.route-statistics';

    protected static ?string $navigationGroup = 'Statistics';

    protected static string $userName = 'email';

    protected static ?string $teamModel = null;

    protected static ?string $teamColumn = 'team_id';

    protected static string $dateFormat = 'M j, Y H:i';

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

    public static function getAggregate(): string
    {
        return config('route-statistics.aggregate');
    }

    protected function getHeaderWidgets(): array
    {
        return [
            RouteStatisticsOverview::class,
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(RouteStatistic::query())
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

                SelectFilter::make('method')
                    ->label(__('filament-route-statistics::filament-route-statistics.table.columns.method'))
                    ->multiple()
                    ->options(fn () => RouteStatistic::select('method')->distinct()->pluck('method', 'method'))
                    ->attribute('method'),

                SelectFilter::make('status')
                    ->label(__('filament-route-statistics::filament-route-statistics.table.columns.status'))
                    ->multiple()
                    ->options(fn () => RouteStatistic::select('status')->distinct()->pluck('status', 'status'))
                    ->attribute('status'),

                // SelectFilter::make('route')
                //     ->label(__('filament-route-statistics::filament-route-statistics.table.columns.route'))
                //     ->multiple()
                //     ->options(fn () => RouteStatistic::select('route')->distinct()->pluck('route', 'route'))
                //     ->attribute('route'),

                Filter::make('date')
                    ->form(function () {
                        $time = static::getAggregate();
                        return [
                            DateTimePicker::make('created_from')
                                ->time($time === 'MINUTE' || $time === 'HOUR')
                                ->seconds(false),
                            DateTimePicker::make('created_until')
                                ->time($time === 'MINUTE'  || $time === 'HOUR')
                                ->seconds(false),
                        ];
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->where('date', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->where('date', '<=', $date),
                            );
                    })
            ])
            ->defaultSort(static::getSortColumn(), static::getSortDirection());
    }
}
