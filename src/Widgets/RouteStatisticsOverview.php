<?php

namespace Amendozaaguiar\FilamentRouteStatistics\Widgets;

use Bilfeldt\LaravelRouteStatistics\Models\RouteStatistic;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class RouteStatisticsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '60s';

    public static function getAggregate(): string
    {
        return config('route-statistics.aggregate');
    }

    public static function getMethods(): array
    {
        $order = ["GET", "POST", "PUT", "PATCH", "DELETE"];

        $methods = RouteStatistic::select('method')
            ->distinct()
            ->pluck('method')
            ->toArray();

        // Ordenar los métodos según el orden especificado
        usort($methods, function ($a, $b) use ($order) {
            $posA = array_search($a, $order);
            $posB = array_search($b, $order);
            if ($posA === false && $posB === false) {
                return 0; // Si no se encuentran en el orden especificado, mantén el orden original
            } elseif ($posA === false) {
                return 1; // Si $a no se encuentra en el orden especificado, muévalo después de $b
            } elseif ($posB === false) {
                return -1; // Si $b no se encuentra en el orden especificado, muévalo antes de $a
            } else {
                return $posA - $posB; // Orden según la posición en el arreglo de orden
            }
        });

        return $methods;
    }

    protected function getStats(): array
    {
        $stats = [];

        foreach (self::getMethods() as $value) {
            $stats[] = Stat::make($value, $this->getCountRequestByMethod($value))
                ->description(__('filament-route-statistics::filament-route-statistics.widgets.overview.statistic.description'))
                ->descriptionIcon('heroicon-m-chart-bar', IconPosition::Before)
                ->color(
                    match ($value) {
                        'GET' => 'success',
                        'POST' => 'warning',
                        'PUT' => 'info',
                        'PATCH' => 'gray',
                        'DELETE' => 'danger',
                        default => 'gray'
                    }
                );
        }

        return $stats;
    }

    private function getCountRequestByMethod($method): string
    {
        $query = RouteStatistic::where('method', $method);

        switch (self::getAggregate()) {
            case 'MINUTE':
                $query->whereDate('date', '>=', Carbon::now()->subMinute());
                break;
            case 'HOUR':
                $query->whereDate('date', '>=', Carbon::now()->subHour());
                break;
            case 'DAY':
                $query->whereDate('date', '>=', Carbon::now()->subDay());
                break;
            case 'MONTH':
                $query->whereDate('date', '>=', Carbon::now()->subMonth());
                break;
            case 'YEAR':
                $query->whereDate('date', '>=', Carbon::now()->subYear());
                break;
            default:
                // En caso de que el intervalo de tiempo no esté definido,
                // no aplicamos ninguna restricción adicional en la consulta.
                break;
        }

        return $this->formatNumber($query->sum('counter') ?? 0);
    }

    private function formatNumber($number): string
    {
        $isNegative = $number < 0;
        $number = abs($number);

        $formats = [
            1000000000000 => 'T',
            1000000000 => 'B',
            1000000 => 'M',
            1000 => 'k',
        ];

        foreach ($formats as $limit => $suffix) {
            if ($number >= $limit) {
                $formattedNumber = number_format($number / $limit, 1) . $suffix;
                return $isNegative ? '-' . $formattedNumber : $formattedNumber;
            }
        }

        return $isNegative ? '-' . $number : $number;
    }
}
