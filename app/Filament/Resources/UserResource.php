<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'Personnal informations';

    public static function form(Form $form): Form
    {
        $isAdmin = Auth::user() && Auth::user()->is_admin;

        $schema = [
            Forms\Components\Grid::make(2)->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),



                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(User::class, 'email', ignoreRecord: true),

                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create'),

                Textarea::make('biographie')
                    ->maxLength(65535)
                    ->columnSpan(2),

                FileUpload::make('img')
                    ->image()
                    ->directory('users')
                    ->columnSpan(2),

                TextInput::make('google_scholar')
                    ->url()
                    ->maxLength(255),

                Forms\Components\Section::make('Skills')
                    ->schema([
                        Forms\Components\Repeater::make('skills')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->maxLength(100),
                                Forms\Components\Textarea::make('description')
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->columnSpan(2)
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible(),
                    ]),
            ]),
        ];

        // Only show the admin toggle to admins
        if ($isAdmin) {
            $schema[0]->schema(array_merge($schema[0]->getSchema(), [
                Toggle::make('is_admin')
                    ->label('Administrator')
                    ->inline(false)
            ]));
        }

        return $form->schema($schema);
    }

    public static function table(Table $table): Table
    {
        $isAdmin = Auth::user() && Auth::user()->is_admin;

        $tableColumns = [
            TextColumn::make('name')
                ->searchable()
                ->sortable(),

            TextColumn::make('nickname')
                ->searchable()
                ->sortable(),

            TextColumn::make('email')
                ->searchable()
                ->sortable(),


            TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];

        // Only show is_admin column to admins
        if ($isAdmin) {
            $tableColumns[] = ToggleColumn::make('is_admin')
                ->label('Admin')
                ->sortable()
                ->disabled(!$isAdmin);
        }

        return $table
            ->columns($tableColumns)
            ->filters([
                Tables\Filters\Filter::make('administrators')
                    ->query(fn ($query) => $query->where('is_admin', true))
                    ->label('Show only administrators')
                    ->visible($isAdmin),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(function ($record) {
                        // Only allow users to edit their own info, admins can edit anyone
                        return Auth::id() === $record->id || (Auth::user() && Auth::user()->is_admin);
                    }),
                Tables\Actions\DeleteAction::make()
                    ->visible(function ($record) {
                        // Don't allow users to delete themselves
                        // Only admins can delete users
                        return Auth::id() !== $record->id && (Auth::user() && Auth::user()->is_admin);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => Auth::user() && Auth::user()->is_admin),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    // Prevent non-admins from creating new users
    public static function canCreate(): bool
    {
        return Auth::user() && Auth::user()->is_admin;
    }
}
