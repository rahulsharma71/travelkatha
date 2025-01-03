<?php

namespace App\Filament\Resources\BlogResource\Pages;

use App\Filament\Resources\BlogResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms;
use Illuminate\Support\Facades\Storage;

class EditBlog extends EditRecord
{
    protected static string $resource = BlogResource::class;

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('title')
                ->label('Title')
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('content')
                ->label('Content')
                ->required(),

            Forms\Components\Select::make('author_id')
                ->label('Author')
                ->relationship('author', 'name') // Assuming 'name' field in User model
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
                ->directory('blog-images') // Store in the correct directory
                ->preserveFilenames()
                ->reorderable()
                ->storeFiles()
                ->afterStateHydrated(function ($state, $set, $record) {
                    // Hydrate the images correctly when editing a blog
                    if ($record && $record->images) {
                        $set('images', $record->images->pluck('image_url')->toArray());
                    }
                })
                ->afterStateUpdated(function ($state, $record) {
                    // Handle image updates and saving the new image URLs correctly
                    if ($record && is_array($state)) {
                        // Delete old images if necessary
                        $oldImages = $record->images;  // Get old images
                        foreach ($oldImages as $oldImage) {
                            // Remove the file from the storage
                            Storage::disk('public')->delete('blog-images/' . $oldImage->image_url); 
                            // Delete from the database
                            $oldImage->delete();
                        }

                        // Save new images
                        foreach ($state as $imagePath) {
                            // Clean up the image path (remove 'public/' prefix)
                            $cleanPath = str_replace('public/', '', $imagePath);
                            $record->images()->create(['image_url' => $cleanPath]);
                        }
                    }
                }),
        ];
    }

    // You may also want to define an action for the page
    protected function getActions(): array
    {
        return [
            // Actions\SaveAction::make(), // You don't need to manually define this action
        ];
    }
}
