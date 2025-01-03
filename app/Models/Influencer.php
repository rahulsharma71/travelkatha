<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Influencer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'bio',
        'image',
    ];

    /**
     * Get the user associated with the influencer.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
