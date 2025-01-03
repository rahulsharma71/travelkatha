<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Story extends Model
{
    // Define the table name if it's not following the convention
    protected $table = 'stories';
    
    protected $fillable = ['title', 'content', 'author_id', 'status', 'reviewed_by'];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
    public function images()
    {
        return $this->hasMany(StoryImage::class);
    }
    
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
