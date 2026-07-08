<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'from_user_id',
        'post_id',
        'comment_id',
        'type',
        'data',
        'is_read'
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $appends = [
        'time_ago',
        'message'
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    /**
     * Scopes
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Attributes
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getMessageAttribute()
    {
        $fromUser = $this->fromUser->name ?? 'Someone';
        
        return match($this->type) {
            'like' => "{$fromUser} liked your post",
            'comment' => "{$fromUser} commented on your post",
            'share' => "{$fromUser} shared your post",
            'follow' => "{$fromUser} started following you",
            default => 'New notification'
        };
    }

    /**
     * Mark as read
     */
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    /**
     * Create notification helper
     */
    public static function createNotification($userId, $fromUserId, $type, $postId = null, $commentId = null, $data = [])
    {
        return self::create([
            'user_id' => $userId,
            'from_user_id' => $fromUserId,
            'post_id' => $postId,
            'comment_id' => $commentId,
            'type' => $type,
            'data' => $data,
            'is_read' => false
        ]);
    }
}