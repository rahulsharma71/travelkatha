<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoryImage extends Model
{
    use HasFactory;

    protected $fillable = ['story_id', 'image_url'];

    public function story()
    {
        return $this->belongsTo(Story::class);
    }
}
