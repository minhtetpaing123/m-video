<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VideoController extends Controller
{
    /**
     * Stream video - Redirect to Bunny CDN
     */
    public function stream($path)
    {
        $cdnUrl = config('bunny.cdn_url');
        $videoUrl = $cdnUrl . '/' . ltrim($path, '/');
        
        return redirect($videoUrl);
    }

    /**
     * Get video info from Bunny
     */
    public function getVideoInfo($postId)
    {
        $post = Post::findOrFail($postId);
        
        if (!$post->video_cdn_url) {
            return response()->json([
                'success' => false,
                'message' => 'Video not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'url' => $post->video_cdn_url,
            'thumbnail' => $post->video_thumbnail_url,
            'duration' => $post->video_duration,
            'size' => $post->video_size,
            'status' => $post->video_status
        ]);
    }

    /**
     * List all videos
     */
    public function index(Request $request)
    {
        $posts = Post::with('user')
            ->whereNotNull('video_path')
            ->where('privacy', 'public')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $posts,
            'categories' => Post::getCategories()
        ]);
    }

    /**
     * Get single video
     */
    public function show($id)
    {
        $post = Post::with(['user', 'comments.user', 'reactions'])
            ->whereNotNull('video_path')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $post,
            'video_url' => $post->video_cdn_url,
            'thumbnail_url' => $post->video_thumbnail_url,
            'category_label' => $post->category_label
        ]);
    }
}