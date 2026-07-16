<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

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
     * Show Download Page (with ads)
     */
    public function downloadPage($postId)
    {
        $post = Post::findOrFail($postId);
        
        if (!$post->video_cdn_url) {
            abort(404, 'Video not found');
        }
        
        return view('posts.download', compact('post'));
    }

    /**
     * Download Video with Watermark
     */
    public function downloadFile($postId)
    {
        $post = Post::findOrFail($postId);
        
        if (!$post->video_cdn_url) {
            abort(404, 'Video not found');
        }

        try {
            // 1. Download video from Bunny
            $videoContent = Http::get($post->video_cdn_url)->body();
            
            // 2. Save temp video
            $tempDir = storage_path('app/temp');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }
            
            $tempVideo = $tempDir . '/video_' . $postId . '_' . time() . '.mp4';
            file_put_contents($tempVideo, $videoContent);
            
            // 3. Add watermark using FFmpeg (12px, top left)
            $outputVideo = $tempDir . '/video_watermarked_' . $postId . '_' . time() . '.mp4';
            $watermarkText = 'M-VIDEO';
            $ffmpegPath = '/data/data/com.termux/files/usr/bin/ffmpeg';
            
            // FFmpeg command with text watermark
            $command = $ffmpegPath . ' -i "' . $tempVideo . '" ' .
                       '-vf "drawtext=fontfile=/data/data/com.termux/files/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf:text=' . $watermarkText . ':fontcolor=white:fontsize=12:box=1:boxcolor=black@0.4:boxborderw=3:x=10:y=10" ' .
                       '-c:a copy "' . $outputVideo . '" 2>&1';
            
            Log::info('FFmpeg watermark command: ' . $command);
            shell_exec($command);
            
            // 4. Check if output exists
            if (!file_exists($outputVideo) || filesize($outputVideo) == 0) {
                Log::warning('Watermark failed, using original video');
                $outputVideo = $tempVideo;
            }
            
            // 5. Generate filename: m-video-{title}.mp4
            $title = $post->title ?? 'video';
            $title = preg_replace('/[^a-zA-Z0-9\-]/', '-', $title);
            $title = preg_replace('/-+/', '-', $title);
            $title = trim($title, '-');
            $filename = 'm-video-' . $title . '.mp4';
            
            // 6. Download response
            return response()->download($outputVideo, $filename, [
                'Content-Type' => 'video/mp4',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ])->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            Log::error('Download error: ' . $e->getMessage());
            
            // Fallback: direct download from Bunny
            return redirect()->away($post->video_cdn_url);
        }
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