<?php

namespace App\Filament\Resources\ApplyResource\Widgets;

use App\Enums\ApplyStatus;
use App\Models\Apply;
use Filament\Widgets\DoughnutChartWidget;

class AppliesChart extends DoughnutChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getHeading(): string
    {
        return '申し込みステータス';
    }

    protected function getData(): array
    {
        $data = Apply::select('status')
            ->selectRaw('count(*) as total')
            ->groupBy('status')
            ->orderBy('status')
            ->get();

        $options = [];
        foreach (ApplyStatus::cases() as $case) {
            $key = $data->search(function ($item) use ($case) {
                return $item->status === $case->value;
            });
            if ($key !== false) {
                $options[$case->value] = $data[$key]->total;
            } else {
                $options[$case->value] = 0;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Blog posts created',
                    'data' => $options,
                    'backgroundColor' => ApplyStatus::options(access: 'color'),
                ],
            ],
            'labels' => ApplyStatus::options(),
        ];
    }
}
