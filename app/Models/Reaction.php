<?php
// app/Models/Reaction.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'type'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Reaction types with emojis
     */
    const TYPES = [
        'like' => '👍',
        'love' => '❤️',
        'care' => '🤗',
        'haha' => '😂',
        'wow' => '😮',
        'sad' => '😢',
        'angry' => '😠'
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
     * Scopes
     */
    public function scopeWhereUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeWherePost($query, $postId)
    {
        return $query->where('post_id', $postId);
    }

    public function scopeWhereType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get emoji for reaction type
     */
    public function getEmojiAttribute()
    {
        return self::TYPES[$this->type] ?? '👍';
    }

    /**
     * Get reaction type with emoji
     */
    public function getTypeWithEmojiAttribute()
    {
        return $this->emoji . ' ' . ucfirst($this->type);
    }
}