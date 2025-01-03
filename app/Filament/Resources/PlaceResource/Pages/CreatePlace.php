<?php

namespace App\Filament\Resources\PlaceResource\Pages;

use App\Filament\Resources\PlaceResource;
use App\Models\CityPlace;
use Filament\Resources\Pages\CreateRecord;

class CreatePlace extends CreateRecord
{
    protected static string $resource = PlaceResource::class;

    protected function afterSave(): void
    {
        // Save the relation in city_places table
        if ($this->record && $this->record->city_id) {
            CityPlace::create([
                'city_id' => $this->record->city_id,
                'place_id' => $this->record->id,
            ]);
        }
    }
}
