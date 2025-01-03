<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    // Define the table if it differs from the default pluralized model name
    protected $table = 'countries';  // Assuming your table is 'countries'

    // Define the fillable fields to allow mass assignment
    protected $fillable = ['name', 'code'];  // Adjust according to your table's columns

    // You can define relationships if necessary, for example:
    public function states()
    {
        return $this->hasMany(State::class);  // Assuming 'State' model exists with a 'country_id' foreign key
    }
}
