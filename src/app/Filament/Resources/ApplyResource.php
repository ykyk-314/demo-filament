<?php

namespace App\Filament\Resources;

use App\Enums\ApplyStatus;
use App\Filament\Resources\ApplyResource\Pages;
use App\Filament\Resources\ApplyResource\RelationManagers;
use App\Models\Apply;
use App\Models\Language;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Webbingbrasil\FilamentAdvancedFilter\Filters as AdvancedFilter;

class ApplyResource extends Resource
{
    protected static ?string $model = Apply::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $modelLabel = '申し込み管理';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->name('お名前')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('name_kana')
                            ->name('お名前（カナ）')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\Select::make('status')
                            ->name('審査ステータス')
                            ->required()
                            ->options(ApplyStatus::options())
                            ->placeholder(null)
                            ->default(0),
                    ]),
                Grid::make()
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->name('メールアドレス')
                            ->email()
                            ->required()
                            ->maxLength(100),
                    ]),
                Grid::make()
                    ->schema([
                        Forms\Components\TextInput::make('introducer')
                            ->name('サイト紹介者名')
                    ]),
                Grid::make()
                    ->schema([
                        Forms\Components\TextInput::make('interpreter_number')
                            ->name('通訳案内士番号')
                            ->maxLength(50),
                        Forms\Components\DatePicker::make('interpreter_number_at')
                            ->name('通訳案内士番号取得年月日')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('審査ステータス')
                    ->enum(ApplyStatus::options())
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('お名前')
                    ->extraAttributes(['class' => ''])
                    ->searchable(isIndividual: true),
                Tables\Columns\TextColumn::make('name_kana')
                    ->label('お名前（カナ）')
                    ->hidden(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('申込日時')
                    ->dateTime()
                    ->sortable(),
                // ->disableClick()
                // ->extraAttributes(function ($record) {
                //     return [
                //         'wire:click' => 'filterFromDay("' . $record->created_at->format('Y-m-d') . '", "' . $record->created_at->format('Y-m-d') . '")',
                //         'class' => 'transition hover:text-primary-500 cursor-pointer',
                //     ];
                // }),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('更新日時')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                // AdvancedFilter\TextFilter::make('name')
                //     ->label('お名前')
                //     ->enableClauseLabel()
                //     ->default(AdvancedFilter\TextFilter::CLAUSE_CONTAIN),
                // Tables\Filters\TextFilter::make('name_kana')
                //     ->query(fn (Builder $query, $value) => $query->searchByName($value)),
                // ->query(fn (Builder $query): Builder => $query->where('interpreter_number', 'like', '%{value}%')),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->label('申込日時（FROM）'),
                        Forms\Components\DatePicker::make('created_until')->label('申込日時（UNTIL）'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
                Tables\Filters\SelectFilter::make('status')
                    ->label('審査ステータス')
                    ->multiple()
                    ->options(ApplyStatus::options()),
                Tables\Filters\SelectFilter::make('languages')
                    ->label('通訳対応言語')
                    ->relationship('languages')
                    ->multiple()
                    ->options(Language::all()->pluck('name', 'id')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('status_edit')
                    ->label('一括ステータス変更')
                    ->action(fn (Collection $records, array $data) => $records->each(fn (Apply $record) => $record->fill($data)->save()))
                    ->form([
                        Forms\Components\Select::make('status')
                            ->name('審査ステータス')
                            ->required()
                            ->options(ApplyStatus::options([ApplyStatus::New->value]))
                            ->placeholder('選択してください'),
                    ])
                    ->icon('heroicon-o-pencil')
                    ->deselectRecordsAfterCompletion(),
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ApplyResource\RelationManagers\LanguagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApplies::route('/'),
            'create' => Pages\CreateApply::route('/create'),
            'edit' => Pages\EditApply::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            // ApplyResource\Widgets\ApplySearch::class,
        ];
    }

    protected function applyFilters(Builder $query, array $filters): Builder
    {
        $request = request();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->has('email')) {
            $query->where('email', 'like', '%' . $request->input('email') . '%');
        }

        if ($request->has('interpreter_number_obtained_on')) {
            $query->whereDate('interpreter_number_obtained_on', $request->input('interpreter_number_obtained_on'));
        }

        return $query;
    }
}
