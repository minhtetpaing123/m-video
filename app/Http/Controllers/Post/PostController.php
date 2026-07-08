<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    /**
     * Display a listing of posts (Dashboard)
     */
    public function index()
    {
        try {
            $posts = Post::with(['user', 'comments.user', 'reactions'])
                ->latest()
                ->paginate(10);
                
            return view('dashboard', compact('posts'));
            
        } catch (\Exception $e) {
            Log::error('Error loading posts: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load posts.');
        }
    }

    /**
     * Display posts for a specific user
     */
    public function userPosts(User $user)
    {
        try {
            $posts = Post::where('user_id', $user->id)
                ->with(['comments.user', 'reactions'])
                ->latest()
                ->paginate(10);
                
            return view('user.posts', compact('posts', 'user'));
            
        } catch (\Exception $e) {
            Log::error('Error loading user posts: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load user posts.');
        }
    }

    /**
     * Display the specified post
     */
    public function show(Post $post)
    {
        try {
            $post->load(['user', 'comments.user', 'reactions.user']);
            return view('posts.show', compact('post'));
            
        } catch (\Exception $e) {
            Log::error('Error showing post: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Post not found.');
        }
    }
}