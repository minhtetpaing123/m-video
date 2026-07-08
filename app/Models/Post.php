<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content',
        'image',
        'video',
        'link',
        'link_title',
        'link_thumbnail',
        'privacy',
        'likes_count',
        'comments_count',
        'shares_count',
        'notification_enabled'  // Added
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'likes_count' => 'integer',
        'comments_count' => 'integer',
        'shares_count' => 'integer',
        'notification_enabled' => 'boolean'  // Added
    ];

    protected $appends = [
        'has_media',
        'media_type',
        'image_url',
        'video_url',
        'link_url',
        'link_domain'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }

    // Scopes
    public function scopePublic($query)
    {
        return $query->where('privacy', 'public');
    }

    public function scopeWithMedia($query)
    {
        return $query->where(function($q) {
            $q->whereNotNull('image')
              ->orWhereNotNull('video')
              ->orWhereNotNull('link');
        });
    }

    public function scopeFromUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Attributes
    public function getHasMediaAttribute()
    {
        return !is_null($this->image) || !is_null($this->video) || !is_null($this->link);
    }

    public function getMediaTypeAttribute()
    {
        if ($this->image) {
            return 'image';
        } elseif ($this->video) {
            return 'video';
        } elseif ($this->link) {
            return 'link';
        }
        return null;
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    /**
     * Get video URL
     */
    public function getVideoUrlAttribute()
    {
        return $this->video ? asset('storage/' . $this->video) : null;
    }

    /**
     * Get link URL
     */
    public function getLinkUrlAttribute()
    {
        return $this->link ?? null;
    }

    /**
     * Get domain from link
     */
    public function getLinkDomainAttribute()
    {
        if (!$this->link) return null;
        
        $domain = parse_url($this->link, PHP_URL_HOST);
        return str_replace('www.', '', $domain);
    }

    /**
     * Get reaction summary (count by type)
     */
    public function getReactionSummaryAttribute()
    {
        return $this->reactions()
            ->select('type', \DB::raw('count(*) as total'))
            ->groupBy('type')
            ->pluck('total', 'type')
            ->toArray();
    }

    /**
     * Get user's reaction to this post
     */
    public function getUserReactionAttribute($userId)
    {
        return $this->reactions()
            ->where('user_id', $userId)
            ->value('type');
    }

    /**
     * Check if user liked this post
     */
    public function isLikedBy($userId)
    {
        return $this->reactions()
            ->where('user_id', $userId)
            ->exists();
    }

    /**
     * Get top 3 reactions for display
     */
    public function getTopReactionsAttribute()
    {
        return $this->reactions()
            ->select('type', \DB::raw('count(*) as count'))
            ->groupBy('type')
            ->orderBy('count', 'desc')
            ->limit(3)
            ->get();
    }

    // Boot method (for model events)
    protected static function boot()
    {
        parent::boot();

        // When post is deleted, delete related comments and reactions
        static::deleting(function($post) {
            $post->comments()->delete();
            $post->reactions()->delete();
            
            // Delete files if they exist
            if ($post->image) {
                \Storage::disk('public')->delete($post->image);
            }
            if ($post->video) {
                \Storage::disk('public')->delete($post->video);
            }
        });
    }
}