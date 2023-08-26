<?php

namespace Amendozaaguiar\FilamentRouteStatistics\Resources\RouteStatisticsResource\Widgets;

use Bilfeldt\LaravelRouteStatistics\Models\RouteStatistic;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class RouteStatisticsOverview extends BaseWidget
{
    public $stats;

    public $methods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'ALL'];

    protected function getStats(): array
    {
        return $this->getInformationStats();
    }

    private function getInformationStats()
    {
        $allRequest = 0;
        $stats = ['ALL' => null];
        $charts = [];

        foreach ($this->methods as $method) {

            if ($method === 'ALL') {
                continue;
            }

            $countRequests = $this->getCountRequestGroupByMethod($method);
            $todayRequests = $this->getDiffCountRequestGroupByMethod($method, Carbon::now()->subWeek(), Carbon::today());
            $yesterdayRequests = $this->getDiffCountRequestGroupByMethod($method,  Carbon::now()->subWeek()->subWeek(), Carbon::now()->subWeek());
            $diffRequests = $this->calculateDiff($todayRequests, $yesterdayRequests);
            $description = $this->getDescriptionStat($diffRequests);
            $icon = $this->getIcon($diffRequests);
            $chart = $this->getCountRequestGroupByMethodAndDay($method);
            $color = $this->getColor($diffRequests);

            $stats[$method] = Stat::make($method, $this->formatNumber($countRequests))
                ->description($description)
                ->descriptionIcon($icon)
                ->chart($chart)
                ->color($color);

            $allRequest += $countRequests;
            $allChart[] = $chart;
        }

        //une todos los charts por dia
        $charts = array_merge(...$allChart);

        $stats['ALL'] = Stat::make(trans('filament-route-statistics::filament-route-statistics.widgets.overview.statistic.all'), $this->formatNumber($allRequest))
            ->description(trans('filament-route-statistics::filament-route-statistics.widgets.overview.statistic.description.all'))
            ->chart($charts)
            ->color('info');

        return $stats;
    }

    private function getCountRequestGroupByMethod($method): int
    {
        return RouteStatistic::where('method', $method)->sum('counter');
    }

    private function getDiffCountRequestGroupByMethod($method, $startOfWeek, $endOfWeek): int
    {
        return RouteStatistic::where('method', $method)
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->sum('counter');
    }

    private function getCountRequestGroupByMethodAndDay($method): array
    {
        return RouteStatistic::selectRaw('date(date) as day, sum(counter) as total')
            ->where('method', $method)
            ->groupBy('day')
            ->orderBy('day', 'desc')
            ->limit(7)
            ->pluck('total', 'day')
            ->toArray();
    }

    private function calculateDiff($todayRequest, $yesterdayRequest)
    {
        return $todayRequest - $yesterdayRequest;
    }

    private function getDescriptionStat($diff)
    {
        $diffFormat = $this->formatNumber($diff);

        if ($diff > 0) {
            return $diffFormat . " " . trans('filament-route-statistics::filament-route-statistics.widgets.overview.statistic.increase');
        } elseif ($diff < 0) {
            return $diffFormat . " " . trans('filament-route-statistics::filament-route-statistics.widgets.overview.statistic.decrease');
        } else {
            return trans('filament-route-statistics::filament-route-statistics.widgets.overview.statistic.no-change');
        }
    }

    private function getIcon($diff): string
    {
        if ($diff > 0) {
            return 'heroicon-m-arrow-trending-up';
        } elseif ($diff < 0) {
            return 'heroicon-m-arrow-trending-down';
        } else {
            return 'heroicon-m-arrow-right';
        }
    }

    private function getColor($diff): string
    {
        if ($diff > 0) {
            return 'success';
        } elseif ($diff < 0) {
            return 'danger';
        } else {
            return 'info';
        }
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
