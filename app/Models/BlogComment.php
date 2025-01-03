<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogComment extends Model
{
    // If the table name is not plural, specify it here
    protected $table = 'blog_comments';

    // Fillable attributes for mass assignment
    protected $fillable = ['comment', 'user_id', 'blog_post_id'];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);  // A comment belongs to a user
    }

    public function blogPost()
    {
        return $this->belongsTo(Blog::class);  // A comment belongs to a blog post
    }
}
