<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RevenuResource\Pages;
use App\Models\Revenu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use App\Helpers\DateHelper;


class RevenuResource extends Resource
{
    protected static ?string $model = Revenu::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = null;

    public static function getNavigationGroup(): ?string
    {
        return __('resources.navigation.sales');
    }

    public static function getNavigationBadge(): ?string
    {
        $missingDate = Revenu::firstMissingWorkingDay(DateHelper::lastWorkingDay());

        return $missingDate?->toDateString();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getNavigationBadge() ? 'danger' : null;
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
            Forms\Components\DatePicker::make('date')
                ->label(__('resources.fields.date'))
                ->required()
                ->default(function () {
                    if (! Revenu::query()->exists()) {
                        $today = Carbon::today();

                        if (DateHelper::isWeekend($today)) {
                            return DateHelper::previousWorkingDay($today)->toDateString();
                        }

                        return $today->toDateString();
                    }

                    $referenceDate = DateHelper::lastWorkingDay();
                    $missingDate = Revenu::firstMissingWorkingDay($referenceDate);

                    if ($missingDate) {
                        return $missingDate->toDateString();
                    }

                    return Revenu::nextFillableWorkingDay()->toDateString();
                })
                ->unique(ignorable: fn($record) => $record)
                ->rules([
                    fn () => function (string $attribute, $value, $fail) {
                        if (! $value) {
                            return;
                        }

                        if (Carbon::parse($value)->isWeekend()) {
                            $fail(__('resources.messages.weekend_not_allowed'));
                        }
                    }
                ]),

            Forms\Components\TextInput::make('amount')
                ->label(__('resources.fields.amount'))
                ->numeric()
                ->required()
                ->minValue(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label(__('resources.fields.date'))
                    ->date()->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label(__('resources.fields.amount'))
                    ->money('eur', true)->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('resources.fields.created_at'))
                    ->dateTime(),
            ])
            ->defaultSort('date', 'desc')
            ->filters([
                Tables\Filters\Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label(__('resources.fields.from')),
                        Forms\Components\DatePicker::make('until')->label(__('resources.fields.until')),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn($q, $date) => $q->whereDate('date', '>=', $date))
                            ->when($data['until'], fn($q, $date) => $q->whereDate('date', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getModelLabel(): string
    {
        return __('resources.pages.revenus.singular'); // Example: "Revenu"
    }
    public static function getPluralModelLabel(): string
    {
        return __('resources.pages.revenus.plural'); // Example: "Revenus"
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRevenus::route('/'),
            'create' => Pages\CreateRevenu::route('/create'),
            'edit' => Pages\EditRevenu::route('/{record}/edit'),
        ];
    }
}
