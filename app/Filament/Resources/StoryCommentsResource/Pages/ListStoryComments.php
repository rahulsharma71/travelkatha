<?php

namespace App\Filament\Resources\StoryCommentsResource\Pages;

use App\Filament\Resources\StoryCommentsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStoryComments extends ListRecords
{
    protected static string $resource = StoryCommentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
