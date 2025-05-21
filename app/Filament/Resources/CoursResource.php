<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoursResource\Pages;
use App\Models\Cours;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\ViewField;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CoursResource extends Resource
{
    protected static ?string $model = Cours::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationGroup = 'Content Management';

    /**
     * Helper method to generate PDF URL
     */
    public static function generatePdfUrl($state): string
    {
        // First try the URL from config
        $baseUrl = config('filesystems.disks.do.url');

        // If URL is not set, use the endpoint instead
        if (!$baseUrl) {
            $baseUrl = config('filesystems.disks.do.endpoint');
        }

        // Build the complete URL
        if ($baseUrl && $state) {
            // Ensure there's no double slash when joining paths
            $baseUrl = rtrim($baseUrl, '/');
            $state = ltrim($state, '/');

            return $baseUrl . '/' . $state;
        }

        return '';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('titre')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(2),

                Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpan(2),

                FileUpload::make('content')
                    ->disk('do')
                    ->directory(config('filesystems.disks.do.directory_env'))
                    ->acceptedFileTypes(['application/pdf'])
                    ->helperText('Upload PDF files only')
                    ->downloadable()
                    ->openable()
                    ->previewable() // Enable preview for uploaded files
                    ->columnSpan(1),

                // Preview existing PDF when editing
                Forms\Components\Section::make('PDF Preview')
                    ->schema([
                        ViewField::make('pdf_preview')
                            ->view('components.pdf-preview')
                            ->visible(fn (Forms\Get $get) => (bool)$get('content'))
                    ])
                    ->columnSpan(1)
                    ->visible(fn ($record) => $record && $record->content),

                Hidden::make('user_id')
                    ->default(function () {
                        // Get the authenticated User model
                        $user = Auth::user();

                        if ($user) {
                            return $user->id;
                        }

                        // If no user found, create a new error message
                        throw new \Exception('No authenticated user found. Please log in again.');
                    })
                    ->required(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('titre')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->limit(50),
            ])
            ->filters([
                // Add filters if needed
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalContent(fn ($record) => view('components.pdf-modal', ['record' => $record]))
                    ->modalWidth('4xl'),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCours::route('/'),
            'create' => Pages\CreateCours::route('/create'),
            'edit' => Pages\EditCours::route('/{record}/edit'),
        ];
    }
}
