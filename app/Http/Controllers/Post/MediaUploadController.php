<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MediaUploadController extends Controller
{
    /**
     * Store a new post with text, image, video, or link
     */
    public function store(Request $request)
    {
        try {
            Log::info('=== Post Store Request ===');
            Log::info('Request data:', [
                'content' => $request->content,
                'privacy' => $request->privacy,
                'has_image' => $request->hasFile('image'),
                'has_video' => $request->hasFile('video'),
                'has_link' => $request->filled('link'),
                'all_data' => $request->all()
            ]);

            // Validate request
            $validator = Validator::make($request->all(), [
                'content' => 'nullable|string|max:5000',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
                'video' => 'nullable|mimes:mp4,mov,avi,wmv,flv,3gp,mkv|max:102400',
                'link' => 'nullable|url|max:500',
                'privacy' => 'required|in:public,friends,onlyme'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator->errors())
                    ->withInput();
            }

            // Create new post
            $post = new Post();
            $post->user_id = Auth::id();
            $post->content = $request->content ?? '';
            $post->privacy = $request->privacy;
            $post->likes_count = 0;
            $post->comments_count = 0;
            $post->shares_count = 0;

            // Handle Image Upload
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $file = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('posts/images', $filename, 'public');
                $post->image = $path;
                Log::info('Image uploaded:', ['path' => $path]);
            }

            // Handle Video Upload
            if ($request->hasFile('video') && $request->file('video')->isValid()) {
                $file = $request->file('video');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('posts/videos', $filename, 'public');
                $post->video = $path;
                Log::info('Video uploaded:', ['path' => $path]);
            }

            // Handle External Link
            if ($request->filled('link')) {
                $post->link = $request->link;
                $post->link_title = $this->getLinkTitle($request->link);
                Log::info('Link added:', [
                    'link' => $request->link,
                    'title' => $post->link_title
                ]);
            }

            $post->save();

            Log::info('Post saved successfully:', ['post_id' => $post->id]);

            return redirect()->back()->with('success', 'Post created successfully!');

        } catch (\Exception $e) {
            Log::error('Error creating post: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to create post: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Upload image only (for AJAX preview)
     */
    public function uploadImage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $file = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('temp/images', $filename, 'public');
                $url = Storage::url($path);

                return response()->json([
                    'success' => true,
                    'path' => $path,
                    'url' => $url,
                    'filename' => $filename,
                    'message' => 'Image uploaded successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No image file found'
            ], 400);

        } catch (\Exception $e) {
            Log::error('Image upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload image: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload video only (for AJAX preview)
     */
    public function uploadVideo(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'video' => 'required|mimes:mp4,mov,avi,wmv,flv,3gp,mkv|max:102400',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            if ($request->hasFile('video') && $request->file('video')->isValid()) {
                $file = $request->file('video');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('temp/videos', $filename, 'public');
                $url = Storage::url($path);

                return response()->json([
                    'success' => true,
                    'path' => $path,
                    'url' => $url,
                    'filename' => $filename,
                    'message' => 'Video uploaded successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No video file found'
            ], 400);

        } catch (\Exception $e) {
            Log::error('Video upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload video: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process URL (for pasting video links)
     */
    public function processUrl(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'url' => 'required|url'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid URL'
                ], 422);
            }

            $url = $request->url;
            $domain = parse_url($url, PHP_URL_HOST);
            $domain = str_replace('www.', '', $domain);
            
            $platforms = [
                'youtube.com' => ['name' => 'YouTube', 'icon' => '🎬'],
                'youtu.be' => ['name' => 'YouTube', 'icon' => '🎬'],
                'tiktok.com' => ['name' => 'TikTok', 'icon' => '🎵'],
                'instagram.com' => ['name' => 'Instagram', 'icon' => '📸'],
                'facebook.com' => ['name' => 'Facebook', 'icon' => '👥'],
                'fb.com' => ['name' => 'Facebook', 'icon' => '👥'],
                'twitter.com' => ['name' => 'Twitter', 'icon' => '🐦'],
                'x.com' => ['name' => 'Twitter', 'icon' => '🐦'],
                'vimeo.com' => ['name' => 'Vimeo', 'icon' => '🎥'],
            ];
            
            $platform = ['name' => 'External Link', 'icon' => '🔗'];
            foreach ($platforms as $key => $data) {
                if (str_contains($domain, $key)) {
                    $platform = $data;
                    break;
                }
            }

            return response()->json([
                'success' => true,
                'url' => $url,
                'domain' => $domain,
                'platform' => $platform['name'],
                'icon' => $platform['icon'],
                'title' => $platform['name'] . ' Video',
                'message' => 'URL processed successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('URL process error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to process URL'
            ], 500);
        }
    }

    /**
     * Get link title from URL
     */
    private function getLinkTitle($url)
    {
        $domain = parse_url($url, PHP_URL_HOST);
        $domain = str_replace('www.', '', $domain);
        
        $titles = [
            'youtube.com' => 'YouTube Video',
            'youtu.be' => 'YouTube Video',
            'tiktok.com' => 'TikTok Video',
            'instagram.com' => 'Instagram Post',
            'facebook.com' => 'Facebook Video',
            'fb.com' => 'Facebook Video',
            'twitter.com' => 'Twitter Video',
            'x.com' => 'Twitter Video',
            'vimeo.com' => 'Vimeo Video',
            'dailymotion.com' => 'Dailymotion Video',
        ];
        
        foreach ($titles as $key => $title) {
            if (str_contains($domain, $key)) {
                return $title;
            }
        }
        
        return 'External Link';
    }

    /**
     * Delete temporary file
     */
    public function deleteFile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'path' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid path'
                ], 422);
            }

            $path = $request->path;
            $path = str_replace('storage/', '', $path);
            
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
                return response()->json([
                    'success' => true,
                    'message' => 'File deleted successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);

        } catch (\Exception $e) {
            Log::error('File delete error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete file'
            ], 500);
        }
    }
}