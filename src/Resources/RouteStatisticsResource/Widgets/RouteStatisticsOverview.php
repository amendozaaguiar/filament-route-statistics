<?php

namespace Amendozaaguiar\FilamentRouteStatistics\Resources\RouteStatisticsResource\Widgets;

use Bilfeldt\LaravelRouteStatistics\Models\RouteStatistic;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class RouteStatisticsOverview extends BaseWidget
{
    public $stats;

    public function mount()
    {
        $methods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];

        $changes = $this->calculateChangeForMethods($methods);

        $stats = [];

        foreach ($methods as $method) {
            $total = $this->getCountRequestGroupByMethod($method);
            $stats[] = Stat::make($method, $this->formatNumber($total))
                ->description($this->getDescription($changes[$method]))
                ->descriptionIcon($changes[$method]['icon'])
                ->color($this->getColor($changes[$method]));
        }

        $this->stats =  $stats;
    }

    protected function getStats(): array
    {
        return $this->stats;
    }

    private function calculateChangeInfo($yesterdayTotal, $todayTotal)
    {
        if ($yesterdayTotal === 0) {
            $changePercentage = $todayTotal === 0 ? 0 : 100;
            $changeType = 'increment';
        } else {
            $changePercentage = (($todayTotal - $yesterdayTotal) / $yesterdayTotal) * 100;
            $changeType = $todayTotal > $yesterdayTotal ? 'increment' : ($todayTotal < $yesterdayTotal ? 'decrement' : 'no change');
        }

        $icon = 'heroicon-m-arrow-trending-up';

        if ($changeType === 'decrement') {
            $icon = 'heroicon-m-arrow-trending-down';
        }

        return [
            'changePercentage' => $changePercentage,
            'changeType' => $changeType,
            'icon' => $icon,
        ];
    }

    private function getCountRequestGroupByMethod($method)
    {
        return RouteStatistic::where('method', $method)->sum('counter');
    }

    private function formatNumber($number)
    {
        $formats = [
            1000000000000 => 'T',
            1000000000 => 'B',
            1000000 => 'M',
            1000 => 'k',
        ];

        foreach ($formats as $limit => $suffix) {
            if ($number >= $limit) {
                return number_format($number / $limit, 1) . $suffix;
            }
        }

        return $number;
    }

    private function calculateChangeForMethods(array $methods)
    {
        $changes = [];

        foreach ($methods as $method) {
            $yesterdayTotal = $this->getCountRequestGroupByMethod($method, Carbon::yesterday());
            $todayTotal = $this->getCountRequestGroupByMethod($method, Carbon::today());

            $changes[$method] = $this->calculateChangeInfo($yesterdayTotal, $todayTotal);
        }

        return $changes;
    }

    private function getDescription($changeInfo)
    {
        $changeType = $changeInfo['changeType'];
        $changePercentage = $changeInfo['changePercentage'];

        if ($changeType === 'increment') {
            return "{$changePercentage}% increase";
        } elseif ($changeType === 'decrement') {
            return "{$changePercentage}% decrease";
        } else {
            return 'No change';
        }
    }

    private function getColor($changeInfo)
    {
        $changeType = $changeInfo['changeType'];

        if ($changeType === 'increment') {
            return 'success';
        } elseif ($changeType === 'decrement') {
            return 'danger';
        } else {
            return 'info';
        }
    }
}
