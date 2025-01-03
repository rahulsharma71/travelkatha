<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CityPlace extends Model
{
    protected $table = 'city_places';

    protected $fillable = ['city_id', 'place_id'];

    // Relationship with City
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    // Relationship with Place
    public function place()
    {
        return $this->belongsTo(Place::class, 'place_id');
    }
}
