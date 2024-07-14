<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;

class UsersChart extends ChartWidget
{

    protected static ?string $heading = 'Users';

    protected int | string | array $columnSpan = 2;

    public ?string $filter = 'year';

    protected function getFilters(): ?array
    {
        return [
            // 'today' => 'Today',
            // 'week' => 'Last week',
            'month' => 'Last month',
            'year' => 'This year',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;
        if ($activeFilter == 'month') {
            $userTrend = Trend::model(User::class)->between(
                    start: now()->startOfMonth(),
                    end: now()->endOfMonth()
                )->perDay()
                ->count();
        } else {
            $userTrend = Trend::model(User::class)->between(
                start: now()->startOfYear(),
                end: now()->endOfYear()
            )->perMonth()
                ->count();
        }





        return [
            'datasets' => [
                [
                    'label' => 'New sers',
                    'data' => $userTrend->map(fn ($row) => $row->aggregate),
                ],
            ],
            'labels' => $userTrend->map(fn ($row) => $row->date),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
