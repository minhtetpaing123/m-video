<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content',
        'image',
        'video',
        'video_thumbnail',
        'video_720',
        'video_480',
        'video_360',
        'link',
        'link_title',
        'link_thumbnail',
        'privacy',
        'category',
        'is_mature',
        'likes_count',
        'comments_count',
        'shares_count',
        'notification_enabled'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'likes_count' => 'integer',
        'comments_count' => 'integer',
        'shares_count' => 'integer',
        'notification_enabled' => 'boolean',
        'is_mature' => 'boolean'
    ];

    protected $appends = [
        'has_media',
        'media_type',
        'image_url',
        'video_url',
        'link_url',
        'link_domain',
        'category_label'
    ];

    // ============================================
    // CATEGORIES - Netflix Style
    // ============================================
    public static function getCategories()
    {
        return [
            // Action & Adventure
            'action' => '🎬 Action & Adventure',
            'action_comedy' => '🎬 Action Comedy',
            'action_thriller' => '🎬 Action Thriller',
            'martial_arts' => '🥋 Martial Arts',
            'spy' => '🕵️ Spy',

            // Comedy
            'comedy' => '😂 Comedy',
            'romantic_comedy' => '❤️ Romantic Comedy',
            'dark_comedy' => '🖤 Dark Comedy',
            'standup' => '🎤 Stand-up Comedy',
            'satire' => '🎭 Satire',

            // Drama
            'drama' => '😢 Drama',
            'period_drama' => '📜 Period Drama',
            'crime_drama' => '🔫 Crime Drama',
            'teen_drama' => '🧑‍🎓 Teen Drama',
            'melodrama' => '🎭 Melodrama',

            // Romance
            'romance' => '❤️ Romance',
            'romantic_drama' => '💔 Romantic Drama',

            // Horror & Thriller
            'horror' => '😱 Horror',
            'thriller' => '🔪 Thriller',
            'psychological' => '🧠 Psychological',

            // Sci-Fi & Fantasy
            'sci_fi' => '🔬 Sci-Fi',
            'fantasy' => '🐉 Fantasy',
            'superhero' => '🦸 Superhero',
            'space' => '🚀 Space',

            // Documentary
            'documentary' => '📖 Documentary',
            'biography' => '📝 Biography',
            'history' => '🏛️ History',
            'nature' => '🌿 Nature',

            // Music
            'music' => '🎵 Music',
            'concert' => '🎸 Concert',
            'musical' => '🎭 Musical',

            // Sports
            'sports' => '⚽ Sports',
            'fitness' => '💪 Fitness',

            // Kids & Family
            'kids' => '🧒 Kids',
            'family' => '👨‍👩‍👧 Family',
            'animation' => '🎨 Animation',
            'anime' => '🎌 Anime',

            // TV Shows
            'crime' => '📺 Crime',
            'reality' => '📺 Reality TV',
            'talk_show' => '🎙️ Talk Show',

            // Lifestyle
            'cooking' => '🍳 Cooking',
            'travel' => '✈️ Travel',
            'fashion' => '👗 Fashion',
            'education' => '📚 Education',
            'technology' => '💻 Technology',
            'gaming' => '🎮 Gaming',

            // Other
            'other' => '📌 Other',
        ];
    }

    public function getCategoryLabelAttribute()
    {
        $categories = self::getCategories();
        return $categories[$this->category] ?? '📌 Other';
    }

    public function getCategoryEmojiAttribute()
    {
        $label = $this->category_label;
        return substr($label, 0, 2);
    }

    // ============================================
    // RELATIONSHIPS
    // ============================================
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

    // ============================================
    // SCOPES
    // ============================================
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

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeSafeForGuests($query)
    {
        return $query->where('is_mature', false);
    }

    public function scopeSafeForUser($query, $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->where('is_mature', false)
              ->orWhere('user_id', $userId);
        });
    }

    // ============================================
    // ATTRIBUTES
    // ============================================
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

    public function getImageUrlAttribute()
    {
        return $this->image ? Storage::url($this->image) : null;
    }

    public function getVideoUrlAttribute()
    {
        return $this->video ? Storage::url($this->video) : null;
    }

    public function getLinkUrlAttribute()
    {
        return $this->link ?? null;
    }

    public function getLinkDomainAttribute()
    {
        if (!$this->link) return null;
        
        $domain = parse_url($this->link, PHP_URL_HOST);
        return str_replace('www.', '', $domain);
    }

    public function getReactionSummaryAttribute()
    {
        return $this->reactions()
            ->select('type', \DB::raw('count(*) as total'))
            ->groupBy('type')
            ->pluck('total', 'type')
            ->toArray();
    }

    public function isLikedBy($userId)
    {
        return $this->reactions()
            ->where('user_id', $userId)
            ->exists();
    }

    public function getTopReactionsAttribute()
    {
        return $this->reactions()
            ->select('type', \DB::raw('count(*) as count'))
            ->groupBy('type')
            ->orderBy('count', 'desc')
            ->limit(3)
            ->get();
    }

    // ============================================
    // BOOT
    // ============================================
    protected static function boot()
    {
        parent::boot();

        static::deleting(function($post) {
            $post->comments()->delete();
            $post->reactions()->delete();
            
            // Delete all media files
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            if ($post->video) {
                Storage::disk('public')->delete($post->video);
            }
            if ($post->video_thumbnail) {
                Storage::disk('public')->delete($post->video_thumbnail);
            }
            if ($post->video_720) {
                Storage::disk('public')->delete($post->video_720);
            }
            if ($post->video_480) {
                Storage::disk('public')->delete($post->video_480);
            }
            if ($post->video_360) {
                Storage::disk('public')->delete($post->video_360);
            }
        });
    }
}