<?php

namespace App\Filament\Resources\ApplyResource\Widgets;

use App\Enums\ApplyStatus;
use App\Models\Apply;
use Closure;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestApplies extends BaseWidget
{
    protected function getTableHeading(): string
    {
        return '最新の審査状況';
    }

    protected function getTableQuery(): Builder
    {
        return Apply::query()
            ->orderBy('updated_at', 'desc')
            ->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')
                ->label('名前'),
            Tables\Columns\TextColumn::make('status')
                ->label('審査ステータス')
                ->enum(ApplyStatus::options()),
        ];
    }
}
