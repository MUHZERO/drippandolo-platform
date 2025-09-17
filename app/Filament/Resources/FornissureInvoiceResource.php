<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FornissureInvoiceResource\Pages;
use App\Models\FornissureInvoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;


class FornissureInvoiceResource extends Resource
{
    protected static ?string $model = FornissureInvoice::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-currency-euro';
    protected static ?string $navigationGroup = 'Finance';

    public static function getModelLabel(): string
    {
        return __('resources.pages.fornissure_invoices.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resources.pages.fornissure_invoices.plural');
    }
    public static function canEdit($record): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        if ($user->hasRole('admin')) {
            // Admins can edit only non-return invoices
            return $record->type !== 'return';
        }

        if ($user->hasRole('fornissure')) {
            // Fornissure can edit return invoices they own
            return $record->type === 'return' && $record->fornissure_id === $user->id;
        }

        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('fornissure_id')
                ->relationship(
                    name: 'fornissure',
                    titleAttribute: 'name',
                    modifyQueryUsing: fn($query) => $query->whereHas('roles', fn($q) => $q->where('name', 'fornissure'))
                )
                ->label(__('resources.fields.fornissure'))
                ->searchable()
                ->required(),


            Forms\Components\TextInput::make('reference')
                ->label(__('resources.fields.reference'))
                ->disabled()
                ->default(fn() => 'INV-' . now()->format('Ymd-His')),

            Forms\Components\Select::make('type')
                ->label(__('resources.fields.type_invoice'))
                ->options([
                    'payment' => 'Payment (every Wednesday)',
                    'return'  => 'Return (every 20 days)',
                ])
                ->disabled()
                ->required(),

            Forms\Components\DatePicker::make('period_start')
                ->label(__('resources.fields.period_start'))
                ->disabled(),
            Forms\Components\DatePicker::make('period_end')
                ->label(__('resources.fields.period_end'))
                ->disabled(),

            Forms\Components\TextInput::make('amount')
                ->label(__('resources.fields.amount'))
                ->numeric()
                ->disabled(),

            Forms\Components\Select::make('status')
                ->label(__('resources.fields.status'))
                ->options([
                    'not_paid' => __('resources.statuses.not_paid'),
                    'paid'     => __('resources.statuses.paid'),
                ])
                ->default('not_paid')
                ->required(),

            Forms\Components\FileUpload::make('transaction_image')
                ->label(__('resources.fields.transaction_image'))
                ->image()
                ->directory('invoices/transactions'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('reference')
                ->label(__('resources.fields.reference'))
                ->sortable(),
            Tables\Columns\TextColumn::make('fornissure.name')
                ->label(__('resources.fields.fornissure'))
                ->sortable()
                ->searchable()
                ->visible(fn() => auth()->user()?->hasRole('admin') ?? false),

            Tables\Columns\TextColumn::make('type')
                ->label(__('resources.fields.type_invoice'))
                ->sortable()
                ->formatStateUsing(fn($state) => __("resources.invoice_types.$state"))
                ->badge()
                ->colors([
                    'danger' => 'return',
                    'success' => 'payment',
                ]),
            Tables\Columns\TextColumn::make('period_start')
                ->label(__('resources.fields.period_start'))
                ->sortable()
                ->date(),
            Tables\Columns\TextColumn::make('period_end')
                ->label(__('resources.fields.period_end'))
                ->sortable()
                ->date(),
            Tables\Columns\TextColumn::make('amount')
                ->label(__('resources.fields.amount'))
                ->sortable()
                ->money('eur', true),
            Tables\Columns\TextColumn::make('status')
                ->label(__('resources.fields.status'))
                ->sortable()
                ->formatStateUsing(fn($state) => __("resources.statuses.$state"))
                ->badge()
                ->colors([
                    'danger' => 'not_paid',
                    'success' => 'paid',
                ]),
            Tables\Columns\ImageColumn::make('transaction_image')
                ->label(__('resources.fields.transaction_image'))
                ->toggleable(isToggledHiddenByDefault: true)

                ->square(),
            Tables\Columns\TextColumn::make('created_at')
                ->label(__('resources.fields.created_at'))
                ->sortable()
                ->dateTime(),
        ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('resources.fields.status'))
                    ->options([
                        'not_paid' => __('resources.statuses.not_paid'),
                        'paid'     => __('resources.statuses.paid'),
                    ]),
                Tables\Filters\SelectFilter::make('type')
                    ->label(__('resources.fields.type_invoice'))
                    ->options([
                        'payment' => 'Payment (every Wednesday)',
                        'return'  => 'Return (every 20 days)',
                    ]),
                Tables\Filters\Filter::make('created_at')
                    ->label(__('resources.fields.created_at'))
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label(__('resources.fields.from')),
                        Forms\Components\DatePicker::make('created_until')
                            ->label(__('resources.fields.until')),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn($query, $date) => $query->whereDate('created_at', '>=', $date)
                            )
                            ->when(
                                $data['created_until'],
                                fn($query, $date) => $query->whereDate('created_at', '<=', $date)
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFornissureInvoices::route('/'),
            'create' => Pages\CreateFornissureInvoice::route('/create'),
            'edit' => Pages\EditFornissureInvoice::route('/{record}/edit'),
        ];
    }
}
