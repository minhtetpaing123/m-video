<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'reactions', 'comments'])
            ->where('privacy', 'public')
            ->whereNotNull('video')
            ->latest()
            ->paginate(20);
        
        return view('home', compact('posts'));
    }
}