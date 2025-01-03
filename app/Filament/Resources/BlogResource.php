<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Models\Blog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Support\Facades\Storage;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;
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

                Forms\Components\Select::make('author_id')
                    ->label('Author')
                    ->relationship('author', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                    ])
                    ->required(),

                Forms\Components\FileUpload::make('images')
                    ->label('Blog Images')
                    ->multiple()
                    ->image()
                    ->directory('blog-images')
                    ->preserveFilenames()
                    ->reorderable()
                    ->storeFiles()
                    ->afterStateHydrated(function ($state, $set, $record) {
                        if ($record && $record->images) {
                            $set('images', $record->images->pluck('image_url')->toArray());
                        }
                    })
                    ->saveUploadedFileUsing(function ($file) {
                        return $file->store('blog-images', 'public');
                    })
                    ->afterStateUpdated(function ($state, $record) {
                        if ($record && is_array($state)) {
                            // Existing images in the database
                            $existingImages = $record->images->pluck('image_url')->toArray();

                            // Identify images to delete
                            $imagesToDelete = array_diff($existingImages, $state);

                            foreach ($imagesToDelete as $imagePath) {
                                // Delete image from storage
                                Storage::disk('public')->delete($imagePath);

                                // Delete image record from database
                                $record->images()->where('image_url', $imagePath)->delete();
                            }

                            // Add new images
                            foreach ($state as $imagePath) {
                                if (!in_array($imagePath, $existingImages)) {
                                    $cleanPath = str_replace('public/', '', $imagePath);
                                    $record->images()->create(['image_url' => $cleanPath]);
                                }
                            }
                        }
                    }),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
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
                    ->sortable()
                    ->badge(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\ImageColumn::make('images.image_url')
                    ->label('Images')
                    ->size(50),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                    ]),
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

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }
}
