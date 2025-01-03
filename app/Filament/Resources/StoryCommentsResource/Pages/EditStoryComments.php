<?php

namespace App\Filament\Resources\StoryCommentsResource\Pages;

use App\Filament\Resources\StoryCommentsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStoryComments extends EditRecord
{
    protected static string $resource = StoryCommentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
