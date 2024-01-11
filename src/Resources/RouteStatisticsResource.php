<?php

namespace Amendozaaguiar\FilamentRouteStatistics\Resources;

use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Stringable;
use Filament\Tables\Filters\Filter;
use Illuminate\Foundation\Auth\User;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Bilfeldt\LaravelRouteStatistics\Models\RouteStatistic;
use Amendozaaguiar\FilamentRouteStatistics\Resources\RouteStatisticsResource\Pages\EditRouteStatistics;

use Amendozaaguiar\FilamentRouteStatistics\Resources\RouteStatisticsResource\Pages\ListRouteStatistics;
use Amendozaaguiar\FilamentRouteStatistics\Resources\RouteStatisticsResource\Pages\CreateRouteStatistics;
use Amendozaaguiar\FilamentRouteStatistics\Resources\RouteStatisticsResource\Widgets\RouteStatisticsOverview;

class RouteStatisticsResource extends Resource
{
    protected static ?string $model = RouteStatistic::class;

    public static function getSlug(): string
    {
        if (filled(config('filament-route-statistics.resource.slug'))) {
            return config('filament-route-statistics.resource.slug');
        }

        return str(static::class)
            ->whenContains(
                '\\Resources\\',
                fn (Stringable $slug): Stringable => $slug->afterLast('\\Resources\\'),
                fn (Stringable $slug): Stringable => $slug->classBasename(),
            )
            ->beforeLast('Resource')
            ->plural()
            ->explode('\\')
            ->map(fn (string $string) => str($string)->kebab()->slug())
            ->implode('/');
    }

    public static function getNavigationIcon(): ?string
    {
        return config('filament-route-statistics.resource.navigation_icon');
    }

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
                    ->label(__('filament-route-statistics::filament-route-statistics.table.columns.id'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.' . config('filament-route-statistics.username.column'))
                    ->label(__('filament-route-statistics::filament-route-statistics.table.columns.user.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make(class_exists(config('filament-route-statistics.team.classname')) ? 
                    config('filament-route-statistics.team.classname')::getTable() . '.' . config('filament-route-statistics.team.column') 
                    : 'team_id')
                    ->label(__('filament-route-statistics::filament-route-statistics.table.columns.team'))
                    ->visible(class_exists(config('filament-route-statistics.team.classname')) ? true : false)
                    ->searchable()
                    ->sortable(),

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
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('route')
                    ->label(__('filament-route-statistics::filament-route-statistics.table.columns.route'))
                    ->searchable()
                    ->sortable(),

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
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('ip')
                    ->label(__('filament-route-statistics::filament-route-statistics.table.columns.ip'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('date')
                    ->label(__('filament-route-statistics::filament-route-statistics.table.columns.date'))
                    ->dateTime()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('counter')
                    ->label(__('filament-route-statistics::filament-route-statistics.table.columns.counter'))
                    ->numeric()
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('user_id')
                    ->label(__('filament-route-statistics::filament-route-statistics.table.columns.user'))
                    ->multiple()
                    ->options(fn () => User::select('id', config('filament-route-statistics.username.column'))->pluck(config('filament-route-statistics.username.column'), 'id')->toArray())
                    ->attribute('user_id'),

                SelectFilter::make(class_exists(config('filament-route-statistics.team.classname')) ?
                config('filament-route-statistics.team.classname')::getTable() . '.' . config('filament-route-statistics.team.column')
                : 'team_id')
                    ->label(__('filament-route-statistics::filament-route-statistics.table.columns.team'))
                    ->multiple()
                    ->options(function () {
                        if (class_exists(config('filament-route-statistics.team.classname'))) {
                            $teamClass = config('filament-route-statistics.team.classname');
                            return $teamClass::select('id', config('filament-route-statistics.team.column'))->pluck(config('filament-route-statistics.team.column'), 'id')->toArray();
                        }

                        return [];
                    })
                    ->visible(class_exists(config('filament-route-statistics.team.classname')) ? true : false)
                    ->attribute(class_exists(config('filament-route-statistics.team.classname')) ? config('filament-route-statistics.team.column') : 'team_id'),

                SelectFilter::make('method')
                    ->label(__('filament-route-statistics::filament-route-statistics.table.columns.method'))
                    ->multiple()
                    ->options(fn () => RouteStatistic::select('method')->distinct()->pluck('method', 'method')->toArray())
                    ->attribute('method'),

                SelectFilter::make('status')
                    ->label(__('filament-route-statistics::filament-route-statistics.table.columns.status'))
                    ->multiple()
                    ->options(fn () => RouteStatistic::select('status')->distinct()->pluck('status', 'status')->toArray())
                    ->attribute('status'),

                SelectFilter::make('status')
                    ->label(__('filament-route-statistics::filament-route-statistics.table.columns.route'))
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
            ])
            ->defaultSort(config('filament-route-statistics.sort.column'), config('filament-route-statistics.sort.direction'));
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
