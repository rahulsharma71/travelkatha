<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $table = 'places';

    protected $fillable = ['name', 'description', 'city_id', 'image']; 

    // Relationship with CityPlace
    public function cityPlaces()
    {
        return $this->hasMany(CityPlace::class, 'place_id');
    }
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    
    // Relationship with City through CityPlace
    public function cities()
    {
        return $this->belongsToMany(City::class, 'city_places', 'place_id', 'city_id');
    }
}
