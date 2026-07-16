<?php

use App\Models\Post;
use Illuminate\Support\Str;

if (!function_exists('getCategories')) {
    function getCategories()
    {
        return Post::getCategories();
    }
}

if (!function_exists('getCategoryLabel')) {
    function getCategoryLabel($key)
    {
        $categories = Post::getCategories();
        return $categories[$key] ?? '📌 Other';
    }
}

if (!function_exists('getCategoryEmoji')) {
    function getCategoryEmoji($key)
    {
        $label = getCategoryLabel($key);
        return mb_substr($label, 0, 2);
    }
}

if (!function_exists('getVideoUrl')) {
    function getVideoUrl($post)
    {
        return $post->video_cdn_url ?? $post->video_url ?? null;
    }
}

if (!function_exists('getThumbnailUrl')) {
    function getThumbnailUrl($post)
    {
        return $post->video_thumbnail_url ?? $post->video_thumbnail ?? null;
    }
}

if (!function_exists('formatDuration')) {
    function formatDuration($seconds)
    {
        if (!$seconds) return '00:00';
        
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;
        
        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
        }
        return sprintf('%02d:%02d', $minutes, $secs);
    }
}

if (!function_exists('formatFileSize')) {
    function formatFileSize($bytes)
    {
        if (!$bytes) return '0 B';
        
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = floor(log($bytes, 1024));
        return round($bytes / pow(1024, $i), 2) . ' ' . $units[$i];
    }
}

if (!function_exists('getVideoStatusBadge')) {
    function getVideoStatusBadge($status)
    {
        $badges = [
            'pending' => '<span class="badge badge-warning">⏳ Pending</span>',
            'processing' => '<span class="badge badge-info">🔄 Processing</span>',
            'completed' => '<span class="badge badge-success">✅ Completed</span>',
            'failed' => '<span class="badge badge-danger">❌ Failed</span>',
            'uploading' => '<span class="badge badge-primary">📤 Uploading</span>',
        ];
        
        return $badges[$status] ?? '<span class="badge badge-secondary">Unknown</span>';
    }
}

if (!function_exists('getPrivacyLabel')) {
    function getPrivacyLabel($privacy)
    {
        $labels = [
            'public' => '🌍 Public',
            'friends' => '👥 Friends',
            'onlyme' => '🔒 Only Me',
            'unlisted' => '🔗 Unlisted',
        ];
        
        return $labels[$privacy] ?? $privacy;
    }
}

if (!function_exists('getLinkTypeIcon')) {
    function getLinkTypeIcon($type)
    {
        $icons = [
            'youtube' => '▶️',
            'vimeo' => '🎥',
            'tiktok' => '🎵',
            'instagram' => '📸',
            'facebook' => '👥',
            'twitter' => '🐦',
            'google_drive' => '☁️',
            'mega' => '📁',
            'external' => '🔗',
        ];
        
        return $icons[$type] ?? '🔗';
    }
}

if (!function_exists('truncateText')) {
    function truncateText($text, $length = 100, $suffix = '...')
    {
        if (strlen($text) <= $length) {
            return $text;
        }
        return substr($text, 0, $length) . $suffix;
    }
}