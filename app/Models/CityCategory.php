<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CityCategory extends Model
{
    protected $table = 'city_categories';

    protected $fillable = ['city_id', 'category_id'];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
