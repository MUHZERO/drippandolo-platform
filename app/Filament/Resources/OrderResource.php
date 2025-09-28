<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Navigation\NavigationItem;
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

        $query = parent::getEloquentQuery()
            // Show orders without tracking number first, then by newest created.
            ->orderByRaw('tracking_number IS NULL DESC')
            ->orderBy('created_at', 'desc');

        // If an order_id is provided (e.g., from notifications), filter to it
        if (request()->filled('order_id')) {
            $query->where('id', request()->get('order_id'));
        }

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

    public static function canEdit($record): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }
        // Fornissure cannot open the edit page; they have inline editing only
        if ($user->hasRole('fornissure')) {
            return false;
        }
        // Admins and operators can edit
        return $user->hasRole('admin') || $user->hasRole('operator');
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
                    ->required()
                    ->disabled(fn($record) => $user->hasRole('fornissure')),

                Forms\Components\TextInput::make('size')
                    ->label(__('resources.fields.size'))
                    ->maxLength(100)
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

                Forms\Components\TextInput::make('tracking_number')
                    ->label(__('resources.fields.tracking_number'))
                    ->maxLength(191)
                    ->hidden(fn() => $user->hasRole('operator'))
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
                    ->options(fn() => \App\Models\ConfirmationPrice::query()->orderBy('name')->pluck('name', 'id')->toArray())
                    ->searchable()
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

                Forms\Components\TextInput::make('shopify_order_id')
                    ->label(__('resources.fields.shopify_order_id'))
                    ->maxLength(191)
                    ->disabled(fn($record) => $user->hasRole('fornissure')),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Alert icon for missing tracking
                Tables\Columns\IconColumn::make('missing_tracking_alert')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->color('danger')
                    ->tooltip(fn() => __('resources.tooltips.missing_tracking'))
                    ->visible(function ($record) {
                        $user = auth()->user();
                        return $record
                            && empty($record->tracking_number);
                    }),
                Tables\Columns\TextColumn::make('shopify_order_id')
                    ->label(__('resources.fields.id'))
                    ->sortable(),

                // Combined product image + name
                Tables\Columns\ViewColumn::make('product')
                    ->label(__('resources.fields.product_name'))
                    ->view('filament.tables.columns.order-product')
                    ->sortable(query: fn($query, $direction) => $query->orderBy('product_name', $direction))
                    ->searchable(query: function ($query, $search) {
                        $query->where('product_name', 'like', "%{$search}%");
                    }),

                Tables\Columns\TextColumn::make('size')
                    ->label(__('resources.fields.size'))
                    ->toggleable(),

                // Combined customer name + phone
                Tables\Columns\ViewColumn::make('customer')
                    ->label(__('resources.fields.customer'))
                    ->view('filament.tables.columns.order-customer')
                    ->searchable(query: function ($query, $search) {
                        $query->where(function ($q) use ($search) {
                            $q->where('customer_name', 'like', "%{$search}%")
                                ->orWhere('customer_phone', 'like', "%{$search}%");
                        });
                    }),

                Tables\Columns\TextColumn::make('price')
                    ->label(__('resources.fields.price'))
                    ->money('eur')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

                // tracking_number: inline editable for fornissure, read-only otherwise
                Tables\Columns\TextInputColumn::make('tracking_number')
                    ->label(__('resources.fields.tracking_number'))
                    ->disabled(fn() => ! (auth()->user()?->hasRole('fornissure') ?? false))
                    ->rules(['nullable', 'string', 'max:191'])
                    ->placeholder('')
                    ->extraInputAttributes(function ($record) {
                        $shouldAlert = $record
                            && empty($record->tracking_number);

                        return [
                            'autocomplete' => 'off',
                            'class' => $shouldAlert ? 'order-alert-input' : null,
                            'title' => $shouldAlert ? __('resources.tooltips.missing_tracking') : null,
                        ];
                    })
                    // Cell styling now handled at full-row level
                    ->sortable(),


                // status: inline editable for fornissure
                Tables\Columns\SelectColumn::make('status')
                    ->label(__('resources.fields.status'))
                    ->options([
                        'shipped'   => __('resources.statuses.shipped'),
                        'delivered' => __('resources.statuses.delivered'),
                        'delayed'   => __('resources.statuses.delayed'),
                        'canceled'  => __('resources.statuses.canceled'),
                    ])
                    ->disabled(fn() => ! (auth()->user()?->hasRole('fornissure') ?? false))
                    ->visible(fn() => auth()->user()?->hasRole('fornissure') ?? false)

                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'delivered',
                        'warning' => 'shipped',
                        'danger'  => ['canceled', 'delayed'],
                    ])
                    ->visible(fn() => ! (auth()->user()?->hasRole('fornissure') ?? false))
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

                Tables\Columns\SelectColumn::make('confirmation_price_id')
                    ->label(__('resources.fields.confirmation_price'))
                    ->options(fn() => \App\Models\ConfirmationPrice::query()->orderBy('name')->pluck('name', 'id')->toArray())
                    ->searchable()
                    ->disabled(fn($record) => filled($record->confirmation_price_id))
                    ->visible(fn() => auth()->user()?->hasRole('fornissure') ?? false),
                Tables\Columns\TextColumn::make('confirmationPrice.name')
                    ->label(__('resources.fields.confirmation_price'))
                    ->visible(fn() => ! (auth()->user()?->hasRole('fornissure') ?? false)),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('resources.fields.created_at'))
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->visible(fn() => auth()->user()?->hasRole('admin')),

            ])
            ->recordClasses(function ($record) {
                $classes = [];
                $user = auth()->user();

                if (is_null($record->confirmation_price_id)) {
                    $classes[] = 'bg-red-50';
                }

                // Highlight rows missing a tracking number.
                if (empty($record->tracking_number)) {
                    $classes[] = 'order-alert-row';
                }

                return implode(' ', $classes);
            })
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
                Tables\Actions\EditAction::make()->visible(fn() => auth()->user()?->hasRole('admin') || auth()->user()?->hasRole('operator')),
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
            'confirmed' => Pages\ConfirmedOrders::route('/confirmed'),
        ];
    }

    public static function getNavigationItems(): array
    {
        $items = parent::getNavigationItems();

        $items[] = NavigationItem::make(__('resources.pages.orders_confirmed.plural'))
            ->url(static::getUrl('confirmed'))
            ->icon('heroicon-o-check-circle')
            ->group(static::getNavigationGroup())
            ->visible(fn() => static::canViewAny());

        return $items;
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
