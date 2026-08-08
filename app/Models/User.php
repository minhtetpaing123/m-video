<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory; // ❌ Illuminate\Notifications\Notifiable trait ကို အပြီးတိုင် ဖျက်ထုတ်လိုက်ပါပြီ

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'username',
        'avatar',
        'cover',
        'bio',
        'verified_at',
        'last_seen_at',

        // 🟢 Channel Notification Settings
        'notify_sound',
        'notify_in_app',
        'notify_email',
        'notify_push',

        // 🟢 Social Activity Notification Settings
        'notify_comments',
        'notify_replies',
        'notify_likes',
        'notify_mentions',
        'notify_follows',
        'notify_friend_requests',
        'notify_messages',
        'notify_system_announcements',
        'notify_security_alerts',

        // 🟢 Quiet Hours (DND)
        'quiet_hours_enabled',
        'quiet_hours_start',
        'quiet_hours_end',
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
            'last_seen_at' => 'datetime',
            // Notification settings casts
            'email_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            'comment_notifications' => 'boolean',
            'like_notifications' => 'boolean',
            'follow_notifications' => 'boolean'
        ];
    }

    /**
     * Core Relationships
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

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
    // ✅ CLEAN CUSTOM NOTIFICATIONS RELATIONSHIPS (100% Custom Table Only)
    // ============================================

    /**
     * User လက်ခံရရှိသော Notifications အားလုံး (Custom Table)
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id')->latest();
    }

    /**
     * User မဖတ်ရသေးသော Notifications များ (Custom Table)
     */
    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class, 'user_id')->where('is_read', false);
    }

    /**
     * User ပြုလုပ်ပြီး အခြားသူဆီ သွားသော Notifications များ
     */
    public function sentNotifications()
    {
        return $this->hasMany(Notification::class, 'from_user_id');
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

    // ============================================
    // ✅ FRIENDS RELATIONSHIPS
    // ============================================

    public function friends()
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id')
                    ->withPivot('status', 'created_at')
                    ->wherePivot('status', 'accepted')
                    ->withTimestamps();
    }

    public function friendsOf()
    {
        return $this->belongsToMany(User::class, 'friends', 'friend_id', 'user_id')
                    ->withPivot('status', 'created_at')
                    ->wherePivot('status', 'accepted')
                    ->withTimestamps();
    }

    public function acceptFriendRequest(User $user)
    {
        return \DB::table('friends')
            ->where('user_id', $user->id)
            ->where('friend_id', $this->id)
            ->update(['status' => 'accepted', 'updated_at' => now()]);
    }

    // 2. မိမိထံ လာထားသော Request များ (Received Requests)
    public function friendRequestsReceived()
    {
        return $this->belongsToMany(User::class, 'friends', 'friend_id', 'user_id')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    // 3. မိမိဘက်မှ ပို့ထားသော Request များ (Sent Requests)
    public function friendRequestsSent()
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    // ============================================
    // ✅ FOLLOW RELATIONSHIPS
    // ============================================

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'user_id', 'follower_id')
                    ->withPivot('status', 'notify', 'is_favorite', 'is_muted')
                    ->withTimestamps();
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'user_id')
                    ->withPivot('status', 'notify', 'is_favorite', 'is_muted')
                    ->withTimestamps();
    }

    public function following()
    {
        return $this->followings();
    }

    public function isFollowedBy(User $user)
    {
        return $this->followers()->where('follower_id', $user->id)->exists();
    }

    public function isFollowing(User $user)
    {
        return $this->followings()->where('user_id', $user->id)->exists();
    }

    public function toggleFollow(User $user)
    {
        if ($this->id === $user->id) {
            return;
        }

        if ($this->isFollowing($user)) {
            $this->followings()->detach($user->id);
        } else {
            $this->followings()->attach($user->id);
        }
    }

    public function getFollowersCountAttribute()
    {
        return $this->followers()->count();
    }

    public function getFollowingCountAttribute()
    {
        return $this->followings()->count();
    }

    // ============================================
    // ✅ MUTUAL FRIENDS
    // ============================================

    public function mutualFriends(User $user)
    {
        $myFriends = $this->friends()->pluck('friend_id');
        $theirFriends = $user->friends()->pluck('friend_id');
        
        return User::whereIn('id', $myFriends)
                   ->whereIn('id', $theirFriends)
                   ->get();
    }

    public function mutualFriendsCount(User $user)
    {
        $myFriends = $this->friends()->pluck('friend_id');
        $theirFriends = $user->friends()->pluck('friend_id');
        
        return User::whereIn('id', $myFriends)
                   ->whereIn('id', $theirFriends)
                   ->count();
    }

    // ============================================
    // ✅ ONLINE STATUS METHODS
    // ============================================

    public function updateLastSeen()
    {
        $this->last_seen_at = now();
        $this->save();
    }

    public function isOnline()
    {
        if (Cache::has('user-is-online-' . $this->id)) {
            return true;
        }

        if (!$this->last_seen_at) {
            return false;
        }
        return $this->last_seen_at->diffInMinutes(now()) < 5;
    }

    public function getOnlineStatusAttribute()
    {
        return $this->isOnline() ? 'online' : 'offline';
    }

    public function getLastSeenAttribute()
    {
        return $this->last_seen_at;
    }

    public function getLastSeenHumanAttribute()
    {
        if (!$this->last_seen_at) {
            return 'Never';
        }
        return $this->last_seen_at->diffForHumans();
    }

    // ============================================
    // ✅ CHAT RELATIONSHIPS
    // ============================================

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function unreadMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id')->where('is_read', false);
    }

    public function unreadMessagesCount()
    {
        return $this->unreadMessages()->count();
    }

    public function pushSubscriptions()
    {
        return $this->hasMany(PushSubscription::class);
    }

    // ============================================
    // ✅ AVATAR HELPER
    // ============================================

    public function getAvatarUrlAttribute()
    {
        if (empty($this->avatar)) {
            return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
        }

        if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://')) {
            return $this->avatar;
        }

        if (Storage::disk('public')->exists($this->avatar)) {
            return asset('storage/' . $this->avatar);
        }

        $cdnUrl = env('BUNNY_CDN_URL', config('bunny.cdn_url'));
        if (!empty($cdnUrl)) {
            if (!str_starts_with($cdnUrl, 'http://') && !str_starts_with($cdnUrl, 'https://')) {
                $cdnUrl = 'https://' . $cdnUrl;
            }
            return rtrim($cdnUrl, '/') . '/' . ltrim($this->avatar, '/');
        }

        return asset('storage/' . $this->avatar);
    }

    public function savedPosts()
    {
        return $this->belongsToMany(Post::class, 'saved_posts', 'user_id', 'post_id')
                    ->withTimestamps()
                    ->orderByPivot('created_at', 'desc');
    }

    /**
     * မိမိထံသို့ ရောက်ရှိနေသော Pending Friend Requests များ ဆွဲထုတ်ရန်
     */
    public function pendingFriendRequests()
    {
        return $this->belongsToMany(User::class, 'friends', 'friend_id', 'user_id')
                    ->wherePivot('status', 'pending')
                    ->withPivot('status')
                    ->withTimestamps();
    }
    // ============================================
    // ✅ BLOCK RELATIONSHIPS & HELPER METHODS
    // ============================================

    /**
     * မိမိ Block ထားသော User များ (Blocker)
     */
    public function blockedUsers()
    {
        return $this->belongsToMany(User::class, 'blocks', 'user_id', 'blocked_user_id')
                    ->withPivot(['type', 'reason', 'unblocked_at'])
                    ->withTimestamps();
    }

    /**
     * မိမိအား Block ထားသော User များ (Blocked)
     */
    public function blockedBy()
    {
        return $this->belongsToMany(User::class, 'blocks', 'blocked_user_id', 'user_id')
                    ->withPivot(['type', 'reason', 'unblocked_at'])
                    ->withTimestamps();
    }

    /**
     * မိမိ Block ထားသူ ဟုတ်/မဟုတ် စစ်ရန်
     */
    public function isBlocking($userId): bool
    {
        $id = $userId instanceof User ? $userId->id : $userId;
        return $this->blockedUsers()->where('blocked_user_id', $id)->exists();
    }

    /**
     * မိမိအား Block ထားသူ ဟုတ်/မဟုတ် စစ်ရန်
     */
    public function isBlockedBy($userId): bool
    {
        $id = $userId instanceof User ? $userId->id : $userId;
        return $this->blockedBy()->where('user_id', $id)->exists();
    }

    /**
     * မိမိနှင့် Block အပြန်အလှန် ဖြစ်နေသော User ID များ အားလုံး ရယူရန် ( Feed / Suggestions / Search တွင် ဖျောက်ရန် )
     */
    public function getBlockedUserIdsAttribute(): array
    {
        $myBlocked = $this->blockedUsers()->pluck('users.id');
        $blockedMe = $this->blockedBy()->pluck('users.id');

        return $myBlocked->merge($blockedMe)->unique()->toArray();
    }

    /**
     * User တစ်ယောက်ကို Block ရန် (Auto Unfollow & Unfriend Logic ပါဝင်သည်)
     */
    public function blockUser(int $targetUserId, string $type = 'full', ?string $reason = null): void
    {
        if ($this->id === $targetUserId) return;

        // 1. Block Table ထဲထည့်ခြင်း
        $this->blockedUsers()->syncWithoutDetaching([
            $targetUserId => [
                'type' => $type,
                'reason' => $reason,
                'unblocked_at' => null,
            ]
        ]);

        // 2. Follow / Followers Relationship များကို ဖြုတ်လိုက်ခြင်း
        $this->followings()->detach($targetUserId);
        $this->followers()->detach($targetUserId);

        // 3. Friends Relationship ရှိပါက ဖြုတ်လိုက်ခြင်း
        if (method_exists($this, 'friends')) {
            $this->friends()->detach($targetUserId);
            $this->friendsOf()->detach($targetUserId);
        }
    }

    /**
     * Unblock ပြုလုပ်ရန်
     */
    public function unblockUser(int $targetUserId): void
    {
        $this->blockedUsers()->detach($targetUserId);
    }

}
