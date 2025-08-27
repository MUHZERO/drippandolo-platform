<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpendResource\Pages;
use App\Models\Spend;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Filters\Indicator;
use Illuminate\Database\Eloquent\Builder;

class SpendResource extends Resource
{
    protected static ?string $model = Spend::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
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
        return $query->whereRaw('1 = 0'); // default deny
    }



    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('type')
                ->options([
                    'fornitore' => __('resources.spends_types.fornitore'),
                    'ads' => __('resources.spends_types.ads'),
                    'hosting' => __('resources.spends_types.hosting'),
                    'team' => __('resources.spends_types.team'),
                    'influencer' => __('resources.spends_types.influencer'),
                    'altro' => __('resources.spends_types.altro'),
                ])
                ->required()
                ->reactive(),

            Forms\Components\TextInput::make('amount')
                ->label(__('resources.fields.amount'))
                ->numeric()
                ->required()
                ->minValue(0),

            Forms\Components\TextInput::make('description')
                ->label(__('resources.fields.description'))
                ->visible(fn($get) => $get('type') === 'altro')
                ->required(fn($get) => $get('type') === 'altro')
                ->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('resources.fields.id'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('resources.fields.type'))
                    ->sortable()
                    ->badge(),
                Tables\Columns\TextColumn::make('amount')
                    ->label(__('resources.fields.amount'))
                    ->money('eur', true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label(__('resources.fields.description'))
                    ->sortable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('resources.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                // Filter by type
                SelectFilter::make('type')
                    ->label(__('resources.fields.type'))
                    ->options([
                        'fornitore' => __('resources.spends_types.fornitore'),
                        'ads' => __('resources.spends_types.ads'),
                        'hosting' => __('resources.spends_types.hosting'),
                        'team' => __('resources.spends_types.team'),
                        'influencer' => __('resources.spends_types.influencer'),
                        'altro' => __('resources.spends_types.altro'),
                    ]),

                // Filter by created_at date range
                Filter::make('created_at')
                    ->label(__('resources.fields.created_at'))
                    ->form([
                        Forms\Components\DatePicker::make('from')->label(__('resources.fields.from')),
                        Forms\Components\DatePicker::make('until')->label(__('resources.fields.until')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['until'], fn($q, $date) => $q->whereDate('created_at', '<=', $date));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['from'] ?? null) {
                            $indicators[] = Indicator::make('From ' . $data['from'])->removeField('from');
                        }
                        if ($data['until'] ?? null) {
                            $indicators[] = Indicator::make('Until ' . $data['until'])->removeField('until');
                        }
                        return $indicators;
                    }),
            ])
            ->searchable()
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getModelLabel(): string
    {
        return __('resources.pages.spends.singular'); // Example: "Spend"
    }
    public static function getPluralModelLabel(): string
    {
        return __('resources.pages.spends.plural'); // Example: "Spends"
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSpends::route('/'),
            'create' => Pages\CreateSpend::route('/create'),
            'edit' => Pages\EditSpend::route('/{record}/edit'),
        ];
    }
}
