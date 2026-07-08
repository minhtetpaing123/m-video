<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReactionController extends Controller
{
    /**
     * Store or update reaction
     */
    public function store(Request $request, Post $post)
    {
        try {
            $request->validate([
                'type' => 'required|in:' . implode(',', array_keys(Reaction::TYPES))
            ]);

            $userId = Auth::id();
            
            // Check if user already reacted
            $existingReaction = Reaction::where('user_id', $userId)
                ->where('post_id', $post->id)
                ->first();

            if ($existingReaction) {
                // Update existing reaction
                if ($existingReaction->type === $request->type) {
                    // Remove reaction if same type
                    $existingReaction->delete();
                    $post->decrement('likes_count');
                    $action = 'removed';
                } else {
                    // Change reaction type
                    $existingReaction->update(['type' => $request->type]);
                    $action = 'updated';
                }
            } else {
                // Create new reaction
                Reaction::create([
                    'user_id' => $userId,
                    'post_id' => $post->id,
                    'type' => $request->type
                ]);
                $post->increment('likes_count');
                $action = 'added';
            }

            // Get updated reaction summary
            $summary = $post->reactions()
                ->select('type', \DB::raw('count(*) as count'))
                ->groupBy('type')
                ->pluck('count', 'type');

            return response()->json([
                'success' => true,
                'action' => $action,
                'type' => $request->type,
                'likes_count' => $post->fresh()->likes_count,
                'summary' => $summary,
                'user_reaction' => $this->getUserReaction($post)
            ]);

        } catch (\Exception $e) {
            Log::error('Error processing reaction: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to process reaction'], 500);
        }
    }

    /**
     * Remove reaction
     */
    public function destroy(Post $post)
    {
        try {
            $userId = Auth::id();
            
            $deleted = Reaction::where('user_id', $userId)
                ->where('post_id', $post->id)
                ->delete();

            if ($deleted) {
                $post->decrement('likes_count');
            }

            return response()->json([
                'success' => true,
                'likes_count' => $post->fresh()->likes_count,
                'user_reaction' => null
            ]);

        } catch (\Exception $e) {
            Log::error('Error removing reaction: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to remove reaction'], 500);
        }
    }

    /**
     * Get all reactions for a post
     */
    public function index(Post $post)
    {
        try {
            $reactions = $post->reactions()
                ->with('user:id,name,avatar')
                ->get()
                ->groupBy('type');

            $summary = $post->reactions()
                ->select('type', \DB::raw('count(*) as count'))
                ->groupBy('type')
                ->pluck('count', 'type');

            return response()->json([
                'success' => true,
                'reactions' => $reactions,
                'summary' => $summary,
                'total' => $post->likes_count,
                'user_reaction' => $this->getUserReaction($post)
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting reactions: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to get reactions'], 500);
        }
    }

    /**
     * Get user's reaction to a post
     */
    private function getUserReaction(Post $post)
    {
        $reaction = $post->reactions()
            ->where('user_id', Auth::id())
            ->first();

        return $reaction ? [
            'type' => $reaction->type,
            'emoji' => $reaction->emoji
        ] : null;
    }
}