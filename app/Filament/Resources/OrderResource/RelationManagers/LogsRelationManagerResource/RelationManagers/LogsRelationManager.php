<?php

namespace App\Filament\Resources\OrderResource\RelationManagers\LogsRelationManagerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LogsRelationManager extends RelationManager
{
    protected static string $relationship = 'logs';
    public static function canViewForRecord($ownerRecord, $page): bool
    {
        return auth()->user()?->hasRole('admin'); // only admins
    }
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('resources.fields.user'))
                    ->default('System'),

                Tables\Columns\TextColumn::make('action')
                    ->label(__('resources.fields.action'))
                    ->formatStateUsing(fn($state) => __("resources.logs.actions.$state"))
                    ->badge()
                    ->colors([
                        'success' => 'created',
                        'warning' => 'updated',
                        'danger'  => 'deleted',
                    ]),

                Tables\Columns\TextColumn::make('changes')
                    ->label(__('resources.fields.changes'))
                    ->view('filament.tables.columns.order-log-changes'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('resources.fields.created_at'))
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('action')
                    ->label(__('resources.fields.action'))
                    ->options([
                        'created' => __("resources.logs.actions.created"),
                        'updated' => __("resources.logs.actions.updated"),
                        'deleted' => __("resources.logs.actions.deleted"),
                    ]),

                Tables\Filters\SelectFilter::make('user_id')
                    ->label(__('resources.fields.user'))
                    ->relationship('user', 'name'),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label(__('resources.fields.from')),
                        Forms\Components\DatePicker::make('until')->label(__('resources.fields.until')),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn($q) => $q->whereDate('created_at', '>=', $data['from']))
                            ->when($data['until'], fn($q) => $q->whereDate('created_at', '<=', $data['until']));
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
