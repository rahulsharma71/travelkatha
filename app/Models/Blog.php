<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Blog extends Model
{
    // Define the table name (if it's different from the pluralized model name)
    protected $table = 'blogs';

    // Mass-assignable fields
    protected $fillable = [
        'title',
        'content',
        'author_id',
        'status',
    ];

    // Casts for data types
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships

    /**
     * Get the author of the blog.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get all the images associated with the blog.
     */
    public function images(): HasMany
    {
        return $this->hasMany(BlogImage::class, 'blog_id');
    }

    /**
     * Get all comments for the blog.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(BlogComment::class, 'blog_id');
    }
    protected static function booted()
    {
        static::deleting(function ($blog) {
            // Delete all related images when a blog is deleted
            $blog->images()->delete();
        });
    }
}
