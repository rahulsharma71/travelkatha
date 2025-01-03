<?php

namespace App\Filament\Resources\BlogResource\Pages;

use App\Filament\Resources\BlogResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBlog extends CreateRecord
{
    protected static string $resource = BlogResource::class;

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
