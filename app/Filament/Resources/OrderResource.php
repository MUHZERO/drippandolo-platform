<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = null;

    public static function getNavigationGroup(): ?string
    {
        return __('resources.navigation.sales');
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        $query = parent::getEloquentQuery();

        if (! $user) {
            return $query->whereRaw('1 = 0'); // no results if no user
        }

        if ($user->hasRole('admin')) {
            return $query; // admins see everything
        }

        if ($user->hasRole('operator')) {
            return $query->where('operator_id', $user->id);
        }

        if ($user->hasRole('fornissure')) {
            return $query->where('fornissure_id', $user->id);
        }

        return $query->whereRaw('1 = 0'); // default deny
    }
    public static function form(Form $form): Form
    {
        $user = auth()->user();
        return $form
            ->schema([
                Forms\Components\TextInput::make('product_name')
                    ->label(__('resources.fields.product_name'))
                    ->required()
                    ->maxLength(255)
                    ->disabled(fn($record) => $user->hasRole('fornissure')),

                Forms\Components\FileUpload::make('product_image')
                    ->label(__('resources.fields.product_image'))
                    ->image()
                    ->directory('orders/images')
                    ->disabled(fn($record) => $user->hasRole('fornissure')),


                Forms\Components\TextInput::make('customer_name')
                    ->label(__('resources.fields.customer_name'))
                    ->required()
                    ->maxLength(255)
                    ->disabled(fn($record) => $user->hasRole('fornissure')),

                Forms\Components\TextInput::make('customer_phone')
                    ->label(__('resources.fields.customer_phone'))
                    ->tel()
                    ->required()
                    ->disabled(fn($record) => $user->hasRole('fornissure')),

                Forms\Components\TextInput::make('customer_address')
                    ->label(__('resources.fields.customer_address'))
                    ->required()
                    ->maxLength(255)
                    ->disabled(fn($record) => $user->hasRole('fornissure')),

                Forms\Components\TextInput::make('price')
                    ->label(__('resources.fields.price'))
                    ->numeric()
                    ->required()
                    ->prefix('€')
                    ->disabled(fn($record) => $user->hasRole('fornissure')),

                Forms\Components\Select::make('status')
                    ->label(__('resources.fields.status'))
                    ->options([
                        'shipped'   => __('resources.statuses.shipped'),
                        'delivered' => __('resources.statuses.delivered'),
                        'delayed'   => __('resources.statuses.delayed'),
                        'canceled'  => __('resources.statuses.canceled'),
                    ])
                    ->required(),



                Forms\Components\Select::make('confirmation_price_id')
                    ->label(__('resources.fields.confirmation_price'))
                    ->relationship('confirmationPrice', 'name')
                    ->searchable()
                    ->preload()
                    ->prefix('€')
                    ->hidden(fn() => $user->hasRole('operator'))
                    ->disabled(function ($record) use ($user) {
                        if (! $record) {
                            return false;
                        }
                        if ($user->hasRole('fornissure')) {
                            // if order has an invoice, block editing
                            return $record && $record->invoices()->exists();
                        }
                        // operators always disabled
                        if ($user->hasRole('operator')) {
                            return true;
                        }
                        return false;
                    }),


                Forms\Components\Textarea::make('notes')
                    ->label(__('resources.fields.notes'))
                    ->maxLength(500)
                    ->columnSpanFull()
                    ->disabled(fn($record) => $user->hasRole('fornissure')),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),

                Tables\Columns\TextColumn::make('product_name')
                    ->label(__('resources.fields.product_name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\ImageColumn::make('product_image')
                    ->label(__('resources.fields.product_image'))
                    ->circular(),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label(__('resources.fields.customer_name'))
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('customer_phone')
                    ->label(__('resources.fields.customer_phone'))
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('price')
                    ->label(__('resources.fields.price'))
                    ->money('eur')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => __('resources.statuses.delivered'),
                        'warning' => __('resources.statuses.shipped'),
                        'danger'  => [__('resources.statuses.canceled'), __('resources.statuses.delayed')],
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('operator.name')
                    ->label(__('resources.fields.operator'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(fn() => auth()->user()?->hasRole('admin')),

                Tables\Columns\TextColumn::make('fornissure.name')
                    ->label(__('resources.fields.fornissure'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(fn() => auth()->user()?->hasRole('admin')),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('resources.fields.created_at'))
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->visible(fn() => auth()->user()?->hasRole('admin')),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('resources.fields.status'))
                    ->options([
                        'shipped'   => __('resources.statuses.shipped'),
                        'delivered' => __('resources.statuses.delivered'),
                        'delayed'   => __('resources.statuses.delayed'),
                        'canceled'  => __('resources.statuses.canceled'),
                    ]),

                Tables\Filters\Filter::make('created_at')
                    ->label(__('resources.fields.created_at'))
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label(__('resources.fields.from')),
                        Forms\Components\DatePicker::make('until')
                            ->label(__('resources.fields.until')),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn($q) => $q->whereDate('created_at', '>=', $data['from']))
                            ->when($data['until'], fn($q) => $q->whereDate('created_at', '<=', $data['until']));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(function () {
                            $user = auth()->user();

                            // Admins always can delete
                            if ($user->hasRole('admin')) {
                                return true;
                            }

                            // Operators can delete only if selected orders have no invoices
                            if ($user->hasRole('operator')) {
                                $ids = request()->input('records', []);
                                return ! Order::whereIn('id', $ids)->whereHas('invoices')->exists();
                            }

                            return false;
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\OrderResource\RelationManagers\LogsRelationManagerResource\RelationManagers\LogsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('resources.pages.orders.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resources.pages.orders.plural');
    }
}
