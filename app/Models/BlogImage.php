<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Model;

class BlogImage extends Model
{
    protected $fillable = ['blog_id', 'image_url'];


    protected $casts = [
        'image_url' => 'array',
    ];
    // Relationship with Blog
    public function blog()
    {
        return $this->belongsTo(Blog::class, 'blog_id');
    }
}
