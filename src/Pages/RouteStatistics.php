<?php

namespace Amendozaaguiar\FilamentRouteStatistics\Pages;

use Bilfeldt\LaravelRouteStatistics\Models\RouteStatistic;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class RouteStatistics extends Page implements HasTable
{
    use InteractsWithTable;

    protected $listeners = ['refresh-component' => '$refresh'];

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static string $view = 'filament-route-statistics::pages.route-statistics';

    protected function getActions(): array
    {
        return [
            Action::make('Refrescar')
                ->button()
                ->action('refresh'),
        ];
    }

    public function getHeading(): string | Htmlable
    {
        return trans('filament-route-statistics::filament-route-statistics.table.heading');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament-route-statistics::filament-route-statistics.navigation.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-route-statistics::filament-route-statistics.navigation.label');
    }


    public function refresh(): void
    {

        $this->dispatch('refresh-component');

        Notification::make()
            ->title(trans('filament-route-statistics::filament-route-statistics.table.refresh.notification'))
            ->success()
            ->send();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(RouteStatistic::query()->with(['user'])->orderBy(config('filament-route-statistics.sort.column'), config('filament-route-statistics.sort.direction')))
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
                    ->searchable()
                    ->sortable(),
                TextColumn::make('route')
                    ->label(trans('filament-route-statistics::filament-route-statistics.table.columns.route'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(trans('filament-route-statistics::filament-route-statistics.table.columns.status'))
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
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }
}
