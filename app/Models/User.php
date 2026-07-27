<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone', // ✅ Phone Column အား ထည့်သွင်းပေးလိုက်ပါသည်
        'password',
        'username',
        'avatar',
        'cover', // ✅ Cover Photo / Video အတွက် ထည့်သွင်းပေးလိုက်ပါသည်
        'bio',
        'verified_at',
        // Notification settings
        'email_notifications',
        'push_notifications',
        'comment_notifications',
        'like_notifications',
        'follow_notifications'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'verified_at' => 'datetime',
            // Notification settings casts
            'email_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            'comment_notifications' => 'boolean',
            'like_notifications' => 'boolean',
            'follow_notifications' => 'boolean'
        ];
    }

    /**
     * Relationships
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * User တင်ထားသော Videos (Posts) များ၏ Relationship
     */
    public function videos()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }

    // ============================================
    // ✅ FRIENDS RELATIONSHIP (ထည့်ထားတယ်)
    // ============================================
    
    /**
     * User ရဲ့ သူငယ်ချင်းတွေ (User က friend လုပ်ထားတဲ့သူတွေ)
     */
    public function friends()
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id')
                    ->withPivot('status', 'created_at')
                    ->wherePivot('status', 'accepted')
                    ->withTimestamps();
    }

    /**
     * User ကို friend လုပ်ထားတဲ့သူတွေ (ပြန်လည်)
     */
    public function friendsOf()
    {
        return $this->belongsToMany(User::class, 'friends', 'friend_id', 'user_id')
                    ->withPivot('status', 'created_at')
                    ->wherePivot('status', 'accepted')
                    ->withTimestamps();
    }

    // ============================================
    // ✅ FOLLOW RELATIONSHIPS (အသစ်ထည့်သွင်းပေးလိုက်သည်)
    // ============================================
    
    /**
     * User ကို Follow လုပ်ထားသောသူများ (Followers)
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')->withTimestamps();
    }

    /**
     * User Follow လုပ်ထားသောသူများ (Following)
     */
    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')->withTimestamps();
    }

    /**
     * Notification relationships
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class)->where('is_read', false);
    }

    /**
     * Get user's avatar URL (✅ Robust Fallback Handler)[span_1](start_span)[span_1](end_span)
     */
    public function getAvatarUrlAttribute()
    {
        // 1. Avatar မရှိပါက UI Avatars သို့ ပို့ရန်
        if (empty($this->avatar)) {
            return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
        }

        // 2. http:// သို့မဟုတ် https:// ပါပြီးသား Full URL ဖြစ်ပါက တိုက်ရိုက်သုံးရန်
        if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://')) {
            return $this->avatar;
        }

        // 3. Local Storage ထဲမှာ တကယ်ရှိမရှိ စစ်ဆေးပြီး ရှိလျှင် asset() ဖြင့်ထုတ်ပေးရန်
        if (Storage::disk('public')->exists($this->avatar)) {
            return asset('storage/' . $this->avatar);
        }

        // 4. CDN သုံးထားပါက CDN URL နှင့် ပေါင်းစပ်ပေးရန်
        $cdnUrl = env('BUNNY_CDN_URL', config('bunny.cdn_url'));
        if (!empty($cdnUrl)) {
            if (!str_starts_with($cdnUrl, 'http://') && !str_starts_with($cdnUrl, 'https://')) {
                $cdnUrl = 'https://' . $cdnUrl;
            }
            return rtrim($cdnUrl, '/') . '/' . ltrim($this->avatar, '/');
        }

        // 5. အထက်ပါ အခြေအနေများ မဟုတ်ပါက Default Local Storage URL ပြန်ရန်
        return asset('storage/' . $this->avatar);
    }

    /**
     * Check if user has unread notifications
     */
    public function hasUnreadNotifications()
    {
        return $this->unreadNotifications()->exists();
    }

    /**
     * Get unread notifications count
     */
    public function unreadNotificationsCount()
    {
        return $this->unreadNotifications()->count();
    }
}
