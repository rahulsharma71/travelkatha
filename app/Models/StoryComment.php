<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoryComment extends Model
{
    // Define the properties, relationships, or any other methods you may need for this model

    // If you have a `story_comments` table and want to specify it explicitly
    protected $table = 'story_comments';

    // If you want to allow mass assignment for certain fields
    protected $fillable = ['story_id', 'user_id', 'comment'];

    // Add relationships (e.g., a comment belongs to a user and a story)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function story()
    {
        return $this->belongsTo(Story::class);
    }
}
