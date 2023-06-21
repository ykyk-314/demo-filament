<?php

namespace App\Filament\Resources\ApplyResource\Pages;

use App\Filament\Resources\ApplyResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class ListApplies extends ListRecords
{
    protected static string $resource = ApplyResource::class;

    // public function render(): View
    // {
    //     return view('filament::resources.pages.list-records', [
    //         'page' => $this,
    //         'searchForm' => view('filament.resources.apply-resource.components.search'),
    //     ]);
    // }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // ApplyResource\Widgets\ApplySearch::class,
        ];
    }


    public function isTableSearchable(): bool
    {
        return true;
    }

    protected function applySearchToTableQuery(Builder $query): Builder
    {
        $columnSearchQueries = $this->getTableColumnSearchQueries();
        $globalSearchQuery = $this->getTableSearchQuery();

        if ($columnSearchQueries) {
            foreach ($columnSearchQueries as $column => $searchQuery) {
                if (empty($searchQuery)) {
                    continue;
                }
                if ($column === 'name') {
                    $query->where('name', $searchQuery);
                }
                if ($column === 'name_kana') {
                    $query->where('name_kana', $searchQuery);
                }
            }
        }

        if ($globalSearchQuery) {
            $query->where(function ($query) use ($globalSearchQuery) {
                $query->orWhere('name', 'LIKE', "%{$globalSearchQuery}%")
                    ->orWhere('name_kana', 'LIKE', "%{$globalSearchQuery}%")
                    ->orWhere('email', 'LIKE', "%{$globalSearchQuery}%")
                    ->orWhere('introducer', 'LIKE', "%{$globalSearchQuery}%")
                    ->orWhere('interpreter_number', 'LIKE', "%{$globalSearchQuery}%");
            });
        }

        return $query;
    }
}
