<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConcentrateResource\Pages;
use App\Filament\Resources\ConcentrateResource\RelationManagers;
use App\Models\Concentrate;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ConcentrateResource extends Resource
{
    protected static ?string $model = Concentrate::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';

    protected static ?string $modelLabel = 'concentrado';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('concentrate')
                    ->label('Concentrado')
                    ->required()
                    ->maxLength(150),
                Forms\Components\TextInput::make('chemical_symbol')
                    ->label('Símbolo Químico')
                    ->required()
                    ->maxLength(30),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('concentrate')->label('Concentrado'),
                Tables\Columns\TextColumn::make('chemical_symbol')->label('Símbolo Químico'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageConcentrates::route('/'),
        ];
    }
}
