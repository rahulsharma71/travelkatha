<?php

namespace App\Filament\Resources\StoryResource\Pages;

use App\Filament\Resources\StoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStory extends CreateRecord
{
    protected static string $resource = StoryResource::class;

    protected function afterCreate(): void
    {
        // Handle file uploads manually after the blog is created
        $images = $this->data['images'] ?? [];

        foreach ($images as $imagePath) {
            $cleanPath = str_replace('public/', '', $imagePath);
            $this->record->images()->create(['image_url' => $cleanPath]);
        }
    }
}
