<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StoryResource\Pages;
use App\Models\Story;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;

class StoryResource extends Resource
{
    protected static ?string $model = Story::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('content')
                    ->label('Content')
                    ->required(),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending')
                    ->required(),

                Forms\Components\Select::make('author_id')
                    ->label('Author')
                    ->options(
                        \App\Models\User::all()->pluck('name', 'id')->toArray()
                    )
                    ->disabled()
                    ->required(),

                    FileUpload::make('images') // File upload for story images
                    ->label('Story Images')
                    ->multiple()
                    ->image()
                    ->directory('story-images') // Directory in storage/app/public/story-images
                    ->preserveFilenames()
                    ->reorderable()
                    ->storeFiles() // Ensure files are stored
                    ->saveUploadedFileUsing(function ($file) {
                        // Store the file in the 'story-images' folder inside 'public'
                        $filePath = $file->store('story-images', 'public'); // Store in 'public' disk, which points to storage/app/public
                        return $filePath;
                    })
                    ->afterStateHydrated(function ($state, $set, $record) {
                        // Load existing images when editing
                        if ($record && $record->images) {
                            $set('images', $record->images->pluck('image_url')->toArray());
                        }
                    })
                    ->afterStateUpdated(function ($state, $record) {
                        // Save images to the database during creation or update
                        if ($record && is_array($state)) {
                            foreach ($state as $imagePath) {
                                // Make sure we're storing only the relative path in the database
                                $cleanPath = str_replace('public/', '', $imagePath); // Remove 'public/' to store relative path
                                $record->images()->create(['image_url' => $cleanPath]);
                            }
                        }
                    }),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('author.name')
                    ->label('Author')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->sortable(),

                Tables\Columns\ImageColumn::make('images.image_url') 
                    ->label('Images')
                    ->size(50)
                    ->getStateUsing(function (Story $record) {
                        return $record->images->first()->image_url ?? null;
                    }),
            ])
            ->filters([
                // Add filters if necessary (e.g., by status)
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->action(function (Story $record) {
                        $record->update(['status' => 'approved', 'reviewed_by' => auth()->id()]);
                    })
                    ->color('success'),

                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->action(function (Story $record) {
                        $record->update(['status' => 'rejected', 'reviewed_by' => auth()->id()]);
                    })
                    ->color('danger'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Define relationships if necessary
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStories::route('/'),
            'create' => Pages\CreateStory::route('/create'),
            'edit' => Pages\EditStory::route('/{record}/edit'),
        ];
    }
}
