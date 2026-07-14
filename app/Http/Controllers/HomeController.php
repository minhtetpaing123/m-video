<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the home page with video feed
     */
    public function index(Request $request, $category = null)
    {
        // ============================================
        // ပြင်ဆင်ချက် - Video ရော Link ရော အကုန်ယူမယ်
        // ============================================
        $query = Post::with(['user', 'reactions', 'comments'])
            ->where('privacy', 'public');
        
        // ============================================
        // မူရင်း whereNotNull('video') ကိုဖယ်လိုက်ပါ
        // ============================================
        // ဒါက Video ပဲယူတာမို့ Link အတွက် မပြတာ
        // ->whereNotNull('video'); // ← ဒါကိုဖယ်လိုက်ပါ

        // ============================================
        // 18+ CONTENT FILTER - ADDED
        // ============================================
        // Check if it's 18+ route
        if ($request->routeIs('category.18plus')) {
            $category = '18plus';
        }

        // ============================================
        // CATEGORY FILTER - MODIFIED
        // ============================================
        if ($category == '18plus') {
            // 18+ Category - Show only mature content
            $query->where('is_mature', true);
        } elseif ($category) {
            // Normal Categories - Hide 18+ content
            $query->where('category', $category)
                  ->where('is_mature', false);
        } else {
            // All Categories (Default) - Hide 18+ content
            $query->where('is_mature', false);
        }

        // ============================================
        // SEARCH (Optional) - UNCHANGED
        // ============================================
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('content', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Get posts with pagination
        $posts = $query->latest()->paginate(20);

        // Get all categories for filter
        $categories = Post::getCategories();

        return view('home', compact('posts', 'categories'));
    }
}