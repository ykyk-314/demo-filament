<?php

namespace App\Filament\Resources\ApplyResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class ApplyOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';

    protected function getCards(): array
    {
        return [
            Card::make('Unique views', rand(150,500).'.'.rand(1,9).'k')
                ->description(rand(20,150).'k increase')
                ->descriptionIcon('heroicon-s-trending-up')
                ->chart([rand(1,22), rand(23,43), 10, rand(2,19), rand(12,39), 4, 17])
                ->color('success'),
            Card::make('Bounce rate', rand(10,55).'%')
                ->description(rand(3,10).'% increase')
                ->descriptionIcon('heroicon-s-trending-down')
                ->color('danger'),
            Card::make('Average time on page', rand(1,5).':'.rand(10,50))
                ->description(rand(1,5).'% increase')
                ->descriptionIcon('heroicon-s-trending-up')
                ->color('success'),

            Card::make('Processed', rand(150,500).'.'.rand(1,9).'k')
                ->color('success')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'wire:click' => '$emitUp("setStatusFilter", "processed")',
                ]),
        ];
    }
}
