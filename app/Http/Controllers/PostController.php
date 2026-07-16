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
     * Display a listing of posts.
     */
    public function index()
    {
        $posts = Post::with('user')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('dashboard', compact('posts'));
    }

    /**
     * Display the specified post.
     */
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    /**
     * Display user's posts.
     */
    public function userPosts($userId)
    {
        $posts = Post::with('user')
            ->where('user_id', $userId)
            ->where('privacy', 'public')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('posts.index', compact('posts'));
    }
}