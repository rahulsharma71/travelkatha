<?php

namespace App\Filament\Resources\PlaceResource\Pages;

use App\Filament\Resources\PlaceResource;
use App\Models\CityPlace;
use Filament\Resources\Pages\EditRecord;

class EditPlace extends EditRecord
{
    protected static string $resource = PlaceResource::class;

    protected function afterSave(): void
    {
        // Update the relation in city_places table
        if ($this->record && $this->record->city_id) {
            // Remove existing relation for this place
            CityPlace::where('place_id', $this->record->id)->delete();

            // Create new relation
            CityPlace::create([
                'city_id' => $this->record->city_id,
                'place_id' => $this->record->id,
            ]);
        }
    }
}

