<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms;
use App\Filament\Resources\SystemNotificationResource\Pages;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Database\Eloquent\Builder;


class SystemNotificationResource extends Resource
{
    protected static ?string $model = DatabaseNotification::class;
    protected static ?string $navigationIcon = 'heroicon-o-bell';
    protected static ?string $navigationGroup = 'System';
    protected static ?string $label = 'Notification';
    protected static ?string $pluralLabel = 'Notifications';

    public static function getNavigationGroup(): ?string
    {
        return __('resources.navigation.system');
    }

    public static function getLabel(): ?string
    {
        return __('resources.pages.notifications.singular');
    }

    public static function getPluralLabel(): ?string
    {
        return __('resources.pages.notifications.plural');
    }

    public static function getNavigationBadge(): ?string
    {
        $user = auth()->user();

        $count = $user?->unreadNotifications()->count() ?? 0;

        return $count > 0 ? (string) $count : null;
    }


    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger'; // red badge
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('notifiable_id', auth()->id())
            ->where('notifiable_type', auth()->user()?->getMorphClass());
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            //->recordUrl(fn($record) => static::getNotificationUrl($record))
            ->recordAction('open')
            ->columns([

                Tables\Columns\TextColumn::make('id')
                    ->label(__('resources.fields.id'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('type')
                    ->label(__('resources.fields.type'))
                    ->formatStateUsing(fn($state) => class_basename($state))
                    ->sortable(),

                // subject/title
                Tables\Columns\TextColumn::make('data.key')
                    ->label(__('resources.fields.title'))
                    ->formatStateUsing(
                        fn($state, $record) =>
                        $state ? __($state . '.subject', $record->data['params'] ?? []) : null
                    ),

                // message/body
                Tables\Columns\TextColumn::make('data.key')
                    ->label(__('resources.fields.message'))
                    ->formatStateUsing(
                        fn($state, $record) =>
                        $state ? __($state . '.body', $record->data['params'] ?? []) : null
                    )
                    ->wrap(),

                Tables\Columns\IconColumn::make('read_at')
                    ->label(__('resources.fields.read_at'))
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('resources.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('read')
                    ->label(__('resources.fields.read'))
                    ->placeholder(__('resources.fields.all'))
                    ->trueLabel(__('resources.fields.read'))
                    ->falseLabel(__('resources.fields.unread'))
                    ->queries(
                        true: fn($query) => $query->whereNotNull('read_at'),
                        false: fn($query) => $query->whereNull('read_at'),
                    ),
            ])
            ->actions([
                Tables\Actions\Action::make('open')
                    ->label(__('resources.fields.open'))
                    //->hidden()
                    ->action(function ($record, $livewire) {
                        $record->markAsRead();

                        $type = class_basename($record->type);

                        $url = match ($type) {
                            'OrderStatusChangedNotification',
                            'OrderCreatedNotification',
                            'OrderNeedsConfirmationNotification'
                            => isset($record->data['order_id'])
                                ? route('filament.admin.resources.orders.edit', $record->data['order_id'])
                                : null,

                            'DailyOrdersSummaryNotification'
                            => route('filament.admin.resources.orders.index', [
                                'date' => $record->data['params']['date'] ?? null,
                            ]),

                            'MissingRevenueNotification'
                            => route('filament.admin.resources.revenu.index', [
                                'date' => $record->data['params']['date'] ?? null,
                            ]),

                            default => null,
                        };

                        if ($url) {
                            return $livewire->redirect($url, navigate: true);
                        }

                        $livewire->notify('warning', 'This notification has no destination.');
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }


    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([]); // no editing here
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSystemNotifications::route('/'),
        ];
    }
}
