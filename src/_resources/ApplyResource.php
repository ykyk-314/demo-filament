<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplyResource\Pages;
use App\Filament\Resources\ApplyResource\RelationManagers;
use App\Filament\Resources\ApplyResource\RelationManagers\LanguagesRelationManager;
use App\Models\Apply;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ApplyResource extends Resource
{
    protected static ?string $model = Apply::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

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
                        Forms\Components\TextInput::make('email')
                            ->name('メールアドレス')
                            ->email()
                            ->required()
                            ->maxLength(100),
                    ]),
                Grid::make()
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->name('審査ステータス')
                            ->required()
                            ->options([
                                0 => '新規受付',
                                1 => '面接案内済み',
                                2 => '審査中',
                                3 => '合格',
                                4 => '不合格',
                            ])
                            ->placeholder(null)
                            ->default(0),
                        Repeater::make('languages')
                            ->relationship()
                            ->schema([
                                
                            ]),
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
                Grid::make()
                    ->schema([]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('お名前'),
                Tables\Columns\TextColumn::make('status')
                    ->label('審査ステータス')
                    ->enum([
                        0 => '新規受付',
                        1 => '面接案内済み',
                        2 => '審査中',
                        3 => '合格',
                        4 => '不合格',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('interpreter_number')
                    ->label('通訳案内士番号'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('更新日時')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('name')
                    ->label('お名前')
                    ->query(fn (Builder $query): Builder => $query->where('name', 'like', '%{value}%')),
                Tables\Filters\SelectFilter::make('status')
                    ->label('審査ステータス')
                    ->multiple()
                    ->options([
                        0 => '新規受付',
                        1 => '面接案内済み',
                        2 => '審査中',
                        3 => '合格',
                        4 => '不合格',
                    ]),
                Tables\Filters\Filter::make('interpreter_number')
                    ->label('通訳案内士番号')
                    ->query(fn (Builder $query): Builder => $query->where('interpreter_number', 'like', '%{value}%')),
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
                            ->options([
                                1 => '面接案内済み',
                                2 => '審査中',
                                3 => '合格',
                                4 => '不合格',
                            ])
                            ->placeholder('選択してください'),
                    ])
                    ->icon('heroicon-o-pencil')
                    ->deselectRecordsAfterCompletion(),
                // Tables\Actions\DeleteBulkAction::make()->color('danger'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            LanguagesRelationManager::class,
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
}
