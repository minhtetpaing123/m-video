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
     * Display the specified post - PUBLIC (Guest User တွေလည်းကြည့်လို့ရ)
     */
    public function show(Post $post)
    {
        try {
            // Load relationships
            $post->load(['user', 'comments.user', 'reactions.user']);
            
            // ============================================
            // PRIVACY CHECK
            // ============================================
            
            // If post is public - anyone can view
            if ($post->privacy === 'public') {
                return view('posts.show', compact('post'));
            }
            
            // If post is friends only - only friends can view
            if ($post->privacy === 'friends') {
                // Check if user is logged in
                if (!auth()->check()) {
                    return redirect()->route('login')
                        ->with('error', 'Please login to view this post');
                }
                
                // Check if user is the owner
                if (auth()->id() === $post->user_id) {
                    return view('posts.show', compact('post'));
                }
                
                // Check if user is friends with the post owner
                // (Assuming you have a friends relationship)
                // $isFriend = auth()->user()->friends()->where('friend_id', $post->user_id)->exists();
                // if ($isFriend) {
                //     return view('posts.show', compact('post'));
                // }
                
                // For now, allow if user is logged in (you can add friend check later)
                if (auth()->check()) {
                    return view('posts.show', compact('post'));
                }
                
                return redirect()->route('home')
                    ->with('error', 'You are not authorized to view this post');
            }
            
            // If post is onlyme - only the owner can view
            if ($post->privacy === 'onlyme') {
                if (!auth()->check()) {
                    return redirect()->route('login')
                        ->with('error', 'Please login to view this post');
                }
                
                if (auth()->id() !== $post->user_id) {
                    return redirect()->route('home')
                        ->with('error', 'This post is private');
                }
                
                return view('posts.show', compact('post'));
            }
            
            // Default: redirect to home
            return redirect()->route('home')
                ->with('error', 'You are not authorized to view this post');
            
        } catch (\Exception $e) {
            Log::error('Error showing post: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Post not found.');
        }
    }
}