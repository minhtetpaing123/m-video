<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PostMenuController extends Controller
{
    /**
     * Update post privacy
     */
    public function updatePrivacy(Request $request, Post $post)
    {
        try {
            if ($post->user_id !== Auth::id()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $request->validate(['privacy' => 'required|in:public,friends,private']);
            
            $post->update(['privacy' => $request->privacy]);
            
            return response()->json([
                'success' => true,
                'privacy' => $post->privacy,
                'message' => 'Privacy updated successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Privacy error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update privacy'], 500);
        }
    }

    /**
     * Toggle pin post
     */
    public function togglePin(Post $post)
    {
        try {
            if ($post->user_id !== Auth::id()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            
            $post->update(['is_pinned' => !$post->is_pinned]);
            
            return response()->json([
                'success' => true,
                'pinned' => $post->is_pinned,
                'message' => $post->is_pinned ? 'Post pinned' : 'Post unpinned'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Pin error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to toggle pin'], 500);
        }
    }

    /**
     * Save post
     */
    public function save(Post $post)
    {
        try {
            // TODO: Implement saved posts table
            return response()->json([
                'success' => true,
                'message' => 'Post saved to your collection'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Save error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to save post'], 500);
        }
    }

    /**
     * Hide post
     */
    public function hide(Post $post)
    {
        try {
            // TODO: Implement hidden posts table
            return response()->json([
                'success' => true,
                'message' => 'Post hidden'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Hide error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to hide post'], 500);
        }
    }

    /**
     * Report post
     */
    public function report(Request $request, Post $post)
    {
        try {
            $request->validate(['reason' => 'required|string']);
            
            // TODO: Implement reports table
            
            return response()->json([
                'success' => true,
                'message' => 'Thank you for your report'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Report error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to submit report'], 500);
        }
    }

    /**
     * Block user
     */
    public function blockUser(User $user)
    {
        try {
            // TODO: Implement blocks table
            return response()->json([
                'success' => true,
                'message' => 'User blocked'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Block error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to block user'], 500);
        }
    }
}