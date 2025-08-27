<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = null;

    public static function getNavigationGroup(): ?string
    {
        return __('resources.navigation.user_management');
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
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('resources.fields.name'))
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->label(__('resources.fields.email'))
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),

                Forms\Components\TextInput::make('password')
                    ->label(__('resources.fields.password'))
                    ->password()
                    ->revealable()
                    ->required(fn($livewire) => $livewire instanceof Pages\CreateUser) // required only on create
                    ->dehydrateStateUsing(fn($state) => !empty($state) ? bcrypt($state) : null)
                    ->dehydrated(fn($state) => filled($state)),

                Forms\Components\TextInput::make('phone')
                    ->label(__('resources.fields.phone'))
                    ->maxLength(20),

                Forms\Components\TextInput::make('location')
                    ->label(__('resources.fields.location'))
                    ->maxLength(255),

                Forms\Components\Textarea::make('about')
                    ->label(__('resources.fields.about'))
                    ->maxLength(500)
                    ->columnSpanFull(),

                Forms\Components\Select::make('roles')
                    ->label(__('resources.fields.roles'))
                    ->multiple()
                    ->relationship('roles', 'name') // many-to-many link
                    ->preload()
                    ->required(),
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
                Tables\Columns\TextColumn::make('email')
                    ->label(__('resources.fields.email'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label(__('resources.fields.roles'))
                    ->badge()
                    ->separator(', ')
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('resources.fields.phone'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('location')
                    ->label(__('resources.fields.location'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('resources.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                // Example filter by role
                Tables\Filters\SelectFilter::make('roles')
                    ->label(__('resources.fields.roles'))
                    ->relationship('roles', 'name'),
            ])
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
    public static function getModelLabel(): string
    {
        return __('resources.pages.users.singular'); // Example: "Spend"
    }
    public static function getPluralModelLabel(): string
    {
        return __('resources.pages.users.plural'); // Example: "Spends"
    }

    public static function getPages(): array
    {
        return [
            'index' => UserResource\Pages\ListUsers::route('/'),
            'create' => UserResource\Pages\CreateUser::route('/create'),
            'edit' => UserResource\Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
