<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'parent_id',
        'content'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $appends = [
        'time_ago'
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get replies for this comment (Child Comments)
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->with('user')->latest();
    }

    /**
     * Get parent comment
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Get time ago in human readable format
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at ? $this->created_at->diffForHumans() : '';
    }
}
