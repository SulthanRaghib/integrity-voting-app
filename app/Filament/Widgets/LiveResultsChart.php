<?php

namespace App\Filament\Widgets;

use App\Models\Candidate;
use Filament\Widgets\ChartWidget;

class LiveResultsChart extends ChartWidget
{
    protected ?string $heading = 'Hasil Pemilu Real-Time';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public ?string $pollingInterval = '5s';

    protected function getData(): array
    {
        $candidates = Candidate::withCount('votes')
            ->orderBy('votes_count', 'desc')
            ->get();

        $colors = [
            'rgb(99, 102, 241)',   // Indigo
            'rgb(236, 72, 153)',   // Pink
            'rgb(34, 197, 94)',    // Green
            'rgb(251, 146, 60)',   // Orange
            'rgb(168, 85, 247)',   // Purple
            'rgb(59, 130, 246)',   // Blue
            'rgb(245, 158, 11)',   // Amber
            'rgb(239, 68, 68)',    // Red
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Suara',
                    'data' => $candidates->pluck('votes_count')->toArray(),
                    'backgroundColor' => array_slice($colors, 0, $candidates->count()),
                    'borderColor' => array_slice($colors, 0, $candidates->count()),
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $candidates->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
                'tooltip' => [
                    'enabled' => true,
                ],
            ],
            'scales' => [
                'x' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                    ],
                    'grid' => [
                        'display' => true,
                        'color' => 'rgba(0, 0, 0, 0.05)',
                    ],
                ],
                'y' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}
