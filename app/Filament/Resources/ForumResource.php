<?php
namespace App\Filament\Resources;

use App\Filament\Resources\ForumResource\Pages;
use App\Models\Forum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;

class ForumResource extends Resource
{
    protected static ?string $model = Forum::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Content Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nom_utilisateur')
                    ->label('Nom Utilisateur')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('email_utilisateur')
                    ->label('Email Utilisateur')
                    ->email()
                    ->required(),
                Forms\Components\Textarea::make('question')
                    ->label('Question')
                    ->required(),
                Forms\Components\Select::make('cours_id')
                    ->label('Cours')
                    ->relationship('cours', 'name') // Assuming 'name' is the column to display
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nom_utilisateur')
                    ->label('Nom Utilisateur')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_utilisateur')
                    ->label('Email Utilisateur')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('question')
                    ->label('Question')
                    ->limit(50),
                Tables\Columns\TextColumn::make('cours.name')
                    ->label('Cours')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                // Add filters if needed
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Define any relations if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListForums::route('/'),
            'create' => Pages\CreateForum::route('/create'),
            'edit' => Pages\EditForum::route('/{record}/edit'),
        ];
    }
}
