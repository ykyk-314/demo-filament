<?php

namespace App\Filament\Resources\ApplyResource\Widgets;

use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Component;
use Filament\Resources\Form;
use Filament\Widgets\Widget;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class ApplySearch extends Widget
{

    protected static string $view = 'filament.resources.apply-resource.widgets.apply-search-block';
    
    public function form(Form $form)
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->name('お名前')
                ->required()
                ->maxLength(100),
        ]);
    }
    // public function form(Form $form)
    // {
    //     return $form
    //         ->schema([
    //             Form\Components\TextInput::make('name')
    //                 ->label('名前')
    //                 ->containerClass('w-1/3'),
    //             Form\Components\TextInput::make('email')
    //                 ->label('メールアドレス')
    //                 ->containerClass('w-1/3'),
    //             Form\Components\DateInput::make('interpreter_number_obtained_on')
    //                 ->label('通訳案内士番号取得年月日')
    //                 ->containerClass('w-1/3'),
    //         ])
    //         ->submit('検索')
    //         ->action(Route::currentRouteName());
    // }

    public function handle(Request $request)
    {
        // 検索処理を実装します。
    }
}
