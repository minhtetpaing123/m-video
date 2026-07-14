<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class DescriptionController extends Controller
{
    /**
     * Show the video description/info page
     */
    public function show($id)
    {
        // Find the post
        $post = Post::with(['user', 'reactions', 'comments'])
            ->findOrFail($id);
        
        // Check if post has description
        if (!$post->description) {
            abort(404, 'No description found for this video');
        }
        
        // Increment view count (optional)
        // $post->increment('views_count');
        
        return view('posts.description', compact('post'));
    }
}