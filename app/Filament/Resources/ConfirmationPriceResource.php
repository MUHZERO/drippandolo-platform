<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConfirmationPriceResource\Pages;
use App\Models\ConfirmationPrice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ConfirmationPriceResource extends Resource
{
    protected static ?string $model = ConfirmationPrice::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-euro';
    protected static ?string $navigationGroup = null;

    public static function getNavigationGroup(): ?string
    {
        return __('resources.navigation.sales');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('resources.fields.name'))
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('price')
                    ->label(__('resources.fields.price'))
                    ->numeric()
                    ->required()
                    ->prefix('€'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('id')
                    ->label(__('resources.fields.id'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('resources.fields.name'))
                    ->searchable()->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label(__('resources.fields.price'))
                    ->money('eur')->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('resources.fields.created_at'))
                    ->dateTime()->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }
    public static function getModelLabel(): string
    {
        return __('confirmation_prices.singular'); // Example: "Prezzo di Conferma"
    }

    public static function getPluralModelLabel(): string
    {
        return __('confirmation_prices.plural'); // Example: "Prezzi di Conferma"
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListConfirmationPrices::route('/'),
            'create' => Pages\CreateConfirmationPrice::route('/create'),
            'edit' => Pages\EditConfirmationPrice::route('/{record}/edit'),
        ];
    }
}
