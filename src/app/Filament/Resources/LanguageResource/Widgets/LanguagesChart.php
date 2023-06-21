<?php

namespace App\Filament\Resources\LanguageResource\Widgets;

use App\Models\Language;
use Filament\Widgets\BarChartWidget;

class LanguagesChart extends BarChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getHeading(): string
    {
        return '通訳対応言語';
    }

    protected function getData(): array
    {
        $data = Language::select('name')
            ->selectRaw('count(guide_languages.language_id) as total')
            ->leftJoin('guide_languages', 'languages.id', '=', 'guide_languages.language_id')
            ->groupBy('guide_languages.language_id', 'languages.name')
            ->orderBy('languages.id')
            ->get();

        $set_colors = [
            '#ff6384',
            '#36a2eb',
            '#cc65fe',
            '#ffce56',
            '#4bc0c0',
            '#9966ff',
            '#ff9f40',
            '#ffcd56',
        ];
        return [
            'datasets' => [
                [
                    'label' => self::getHeading(),
                    'data' => $data->pluck('total')->toArray(),
                    'borderWidth' => 1,
                    'backgroundColor' => $set_colors,
                    'borderColor' => $set_colors,
                ],
            ],
            'labels' => $data->pluck('name')->toArray(),
        ];
    }
}
