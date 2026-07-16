<?php

namespace App\Models;

use App\Services\BunnyStorageService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'description',
        'image',
        'video_original',
        'video_path',
        'video_cdn_url',
        'video_thumbnail',
        'video_thumbnail_url',
        'video_duration',
        'video_size',
        'video_status',
        'privacy',
        'category',
        'is_mature',
        'likes_count',
        'comments_count',
        'shares_count',
        'views_count',          // ✅ ထည့်ပါ
        'notification_enabled'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'likes_count' => 'integer',
        'comments_count' => 'integer',
        'shares_count' => 'integer',
        'views_count' => 'integer',     // ✅ ထည့်ပါ
        'notification_enabled' => 'boolean',
        'is_mature' => 'boolean',
        'video_duration' => 'integer',
        'video_size' => 'integer'
    ];

    protected $appends = [
        'has_media',
        'media_type',
        'image_url',
        'video_url',
        'video_thumb_url',
        'category_label'
    ];

    // ============================================
    // CATEGORIES
    // ============================================
    public static function getCategories()
    {
        return [
            'action' => '🎬 Action & Adventure',
            'action_comedy' => '🎬 Action Comedy',
            'action_thriller' => '🎬 Action Thriller',
            'martial_arts' => '🥋 Martial Arts',
            'spy' => '🕵️ Spy',
            'comedy' => '😂 Comedy',
            'romantic_comedy' => '❤️ Romantic Comedy',
            'dark_comedy' => '🖤 Dark Comedy',
            'standup' => '🎤 Stand-up Comedy',
            'satire' => '🎭 Satire',
            'drama' => '😢 Drama',
            'period_drama' => '📜 Period Drama',
            'crime_drama' => '🔫 Crime Drama',
            'teen_drama' => '🧑‍🎓 Teen Drama',
            'melodrama' => '🎭 Melodrama',
            'romance' => '❤️ Romance',
            'romantic_drama' => '💔 Romantic Drama',
            'horror' => '😱 Horror',
            'thriller' => '🔪 Thriller',
            'psychological' => '🧠 Psychological',
            'sci_fi' => '🔬 Sci-Fi',
            'fantasy' => '🐉 Fantasy',
            'superhero' => '🦸 Superhero',
            'space' => '🚀 Space',
            'documentary' => '📖 Documentary',
            'biography' => '📝 Biography',
            'history' => '🏛️ History',
            'nature' => '🌿 Nature',
            'music' => '🎵 Music',
            'concert' => '🎸 Concert',
            'musical' => '🎭 Musical',
            'sports' => '⚽ Sports',
            'fitness' => '💪 Fitness',
            'kids' => '🧒 Kids',
            'family' => '👨‍👩‍👧 Family',
            'animation' => '🎨 Animation',
            'anime' => '🎌 Anime',
            'crime' => '📺 Crime',
            'reality' => '📺 Reality TV',
            'talk_show' => '🎙️ Talk Show',
            'cooking' => '🍳 Cooking',
            'travel' => '✈️ Travel',
            'fashion' => '👗 Fashion',
            'education' => '📚 Education',
            'technology' => '💻 Technology',
            'gaming' => '🎮 Gaming',
            'other' => '📌 Other',
        ];
    }

    public function getCategoryLabelAttribute()
    {
        $categories = self::getCategories();
        return $categories[$this->category] ?? '📌 Other';
    }

    // ============================================
    // BUNNY METHODS
    // ============================================
    
    private function getBunnyStorage()
    {
        return app(BunnyStorageService::class);
    }

    public function uploadVideoToBunny($file)
    {
        $timestamp = now()->timestamp;
        $extension = $file->getClientOriginalExtension();
        $fileName = "{$timestamp}_{$this->id}.{$extension}";
        $path = "videos/{$fileName}";

        $bunny = $this->getBunnyStorage();
        $result = $bunny->upload($file->getContent(), $path);

        if ($result['success']) {
            $this->update([
                'video_original' => $file->getClientOriginalName(),
                'video_path' => $path,
                'video_cdn_url' => $result['cdn_url'],
                'video_size' => $file->getSize(),
                'video_status' => 'completed'
            ]);
        }

        return $result;
    }

    public function deleteVideoFromBunny()
    {
        if (!$this->video_path) {
            return false;
        }

        $bunny = $this->getBunnyStorage();
        $result = $bunny->delete($this->video_path);

        if ($result['success']) {
            $this->update([
                'video_original' => null,
                'video_path' => null,
                'video_cdn_url' => null,
                'video_size' => null,
                'video_status' => null
            ]);
        }

        return $result;
    }

    // ============================================
    // ACCESSORS
    // ============================================
    
    public function getVideoUrlAttribute()
    {
        return $this->video_cdn_url;
    }

    public function getVideoThumbUrlAttribute()
    {
        return $this->video_thumbnail_url;
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? Storage::url($this->image) : null;
    }

    public function getHasMediaAttribute()
    {
        return !is_null($this->image) || 
               !is_null($this->video_path);
    }

    public function getMediaTypeAttribute()
    {
        if ($this->image) {
            return 'image';
        } elseif ($this->video_path) {
            return 'video';
        }
        return null;
    }

    // ============================================
    // REACTION METHODS
    // ============================================
    
    public function isLikedBy($userId)
    {
        return $this->reactions()
            ->where('user_id', $userId)
            ->exists();
    }

    public function getReactionSummaryAttribute()
    {
        return $this->reactions()
            ->select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->pluck('total', 'type')
            ->toArray();
    }

    public function getTopReactionsAttribute()
    {
        return $this->reactions()
            ->select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->orderBy('count', 'desc')
            ->limit(3)
            ->get();
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
              ->orWhereNotNull('video_path');
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

    public function scopeMature($query, $isMature = true)
    {
        return $query->where('is_mature', $isMature);
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
    // BOOT
    // ============================================
    
    protected static function boot()
    {
        parent::boot();

        static::deleting(function($post) {
            $post->comments()->delete();
            $post->reactions()->delete();
            
            // Delete from Bunny
            $post->deleteVideoFromBunny();
        });
    }
}