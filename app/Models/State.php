<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $table = 'states';

    protected $fillable = [
        'name',
        'country_id',
    ];

    /**
     * Relationship with Country Model.
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
