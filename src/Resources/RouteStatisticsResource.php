<?php

namespace Amendozaaguiar\FilamentRouteStatistics\Resources;


use Amendozaaguiar\FilamentRouteStatistics\Resources\RouteStatisticsResource\Pages\CreateRouteStatistics;
use Amendozaaguiar\FilamentRouteStatistics\Resources\RouteStatisticsResource\Pages\EditRouteStatistics;
use Amendozaaguiar\FilamentRouteStatistics\Resources\RouteStatisticsResource\Pages\ListRouteStatistics;
use Amendozaaguiar\FilamentRouteStatistics\Resources\RouteStatisticsResource\Widgets\RouteStatisticsOverview;
use Bilfeldt\LaravelRouteStatistics\Models\RouteStatistic;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;


class RouteStatisticsResource extends Resource
{
    protected static ?string $model = RouteStatistic::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';

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
                        default => 'success'
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
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
