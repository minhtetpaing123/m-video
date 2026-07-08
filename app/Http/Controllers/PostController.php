<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Store a newly created post (Text, Image, Video only)
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
                'all_data' => $request->all()
            ]);

            // Validate request
            $request->validate([
                'content' => 'nullable|string|max:5000',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
                'video' => 'nullable|mimes:mp4,mov,avi,wmv,flv,3gp|max:102400',
                'privacy' => 'required|in:public,friends,onlyme'
            ]);

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
                $imagePath = $request->file('image')->store('posts/images', 'public');
                $post->image = $imagePath;
                Log::info('Image uploaded:', ['path' => $imagePath]);
            }

            // Handle Video Upload
            if ($request->hasFile('video') && $request->file('video')->isValid()) {
                $videoPath = $request->file('video')->store('posts/videos', 'public');
                $post->video = $videoPath;
                Log::info('Video uploaded:', ['path' => $videoPath]);
            }

            // Save post
            $post->save();
            
            Log::info('Post saved successfully:', ['post_id' => $post->id]);

            return redirect()->back()->with('success', 'Post created successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation failed:', $e->errors());
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
                
        } catch (\Exception $e) {
            Log::error('Error creating post: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return redirect()->back()
                ->with('error', 'Failed to create post: ' . $e->getMessage())
                ->withInput();
        }
    }
}