<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'ad_id',
        'user_id',
        'content',
        'parent_id',
    ];

    /**
     * Get the ad that the comment belongs to.
     */
    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }

    /**
     * Get the user that wrote the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent comment (for replies).
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Get the replies to this comment.
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->orderBy('created_at', 'asc');
    }
}
