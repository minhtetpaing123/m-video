<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * အခြေခံ Notification ပို့ရန်
     */
    public static function send(
        $userId, 
        $fromUserId, 
        $type, 
        $message = null, 
        $actionUrl = null, 
        $priority = 'normal', 
        $postId = null, 
        $commentId = null
    ) {
        // မိမိကိုယ်ကို နိုတီ ပြန်မပို့စေရန်
        if ($userId === $fromUserId) {
            return false;
        }

        // Group Key ဖန်တီးခြင်း (ဥပမာ - post_1_like)
        $groupKey = $postId ? "{$type}_{$postId}" : "{$type}_user_{$userId}";

        return Notification::createNotification(
            userId: $userId,
            fromUserId: $fromUserId,
            type: $type,
            postId: $postId,
            commentId: $commentId,
            data: ['message' => $message],
            actionUrl: $actionUrl,
            priority: $priority,
            groupKey: $groupKey,
            expiresAt: self::getExpirationTime($priority) // Priority ပေါ်မူတည်ပြီး သက်တမ်းသတ်မှတ်ရန်
        );
    }

    /**
     * Priority အလိုက် Expire Time သတ်မှတ်ခြင်း (2026 Feature)
     */
    private static function getExpirationTime($priority)
    {
        return match($priority) {
            'urgent' => now()->addDays(7),   // အရေးကြီးရင် ၇ ရက်ထားမယ်
            'high' => now()->addDays(14),    // High ဆိုရင် ၁၄ ရက်
            'normal' => now()->addDays(30),  // ပုံမှန်ဆိုရင် ရက် ၃၀
            'low' => now()->addDays(3),      // အရေးမကြီးရင် ၃ ရက်နဲ့ ဖျောက်မယ်
            default => now()->addDays(30),
        };
    }

    // ==========================================
    // 💡 အလွယ်တကူ ခေါ်သုံးနိုင်သော Helper Methods များ
    // ==========================================

    public static function postLiked($post, $fromUserId)
    {
        return self::send(
            userId: $post->user_id,
            fromUserId: $fromUserId,
            type: 'like',
            actionUrl: route('posts.show', $post->id),
            priority: 'normal',
            postId: $post->id
        );
    }

    public static function postCommented($post, $comment, $fromUserId)
    {
        return self::send(
            userId: $post->user_id,
            fromUserId: $fromUserId,
            type: 'comment',
            message: substr($comment->content, 0, 50) . '...', // Comment အစကိုပါ နိုတီမှာ ပြရန်
            actionUrl: route('posts.show', $post->id),
            priority: 'high',
            postId: $post->id,
            commentId: $comment->id
        );
    }

    public static function userFollowed($followedUserId, $followerId)
    {
        return self::send(
            userId: $followedUserId,
            fromUserId: $followerId,
            type: 'follow',
            actionUrl: route('profile.show', $followerId),
            priority: 'high'
        );
    }

    public static function systemAlert($userId, $message, $actionUrl = null)
    {
        return self::send(
            userId: $userId,
            fromUserId: null, // System ဖြစ်တဲ့အတွက် sender မရှိပါ
            type: 'system',
            message: $message,
            actionUrl: $actionUrl,
            priority: 'urgent'
        );
    }
}
