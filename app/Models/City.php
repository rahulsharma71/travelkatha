<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';

    protected $fillable = ['name', 'state_id', 'country_id', 'image'];

    // Relationship to State
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    // Relationship to Country
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    // Relationship to CityCategory
    public function cityCategories()
    {
        return $this->hasMany(CityCategory::class, 'city_id');
    }

    // Relationship to Category through CityCategory
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'city_categories', 'city_id', 'category_id');
    }
    public function cityPlaces()
    {
        return $this->hasMany(CityPlace::class, 'city_id');
    }

    // Relationship with Place through CityPlace
    public function places()
    {
        return $this->belongsToMany(Place::class, 'city_places', 'city_id', 'place_id');
    }
}
