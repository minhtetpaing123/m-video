<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Reaction;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PostInteractionController extends Controller
{
    /**
     * React to a post (like/love/etc)
     */
    public function react(Request $request, Post $post)
    {
        try {
            $request->validate(['type' => 'required|in:like,love,care,haha,wow,sad,angry']);

            $userId = Auth::id();
            $existingReaction = Reaction::where('user_id', $userId)
                ->where('post_id', $post->id)
                ->first();

            if ($existingReaction) {
                if ($existingReaction->type === $request->type) {
                    $existingReaction->delete();
                    $post->decrement('likes_count');
                } else {
                    $existingReaction->update(['type' => $request->type]);
                }
            } else {
                Reaction::create([
                    'user_id' => $userId,
                    'post_id' => $post->id,
                    'type' => $request->type
                ]);
                $post->increment('likes_count');

                // Create notification for post owner
                if ($post->user_id !== $userId) {
                    Notification::create([
                        'user_id' => $post->user_id,
                        'from_user_id' => $userId,
                        'post_id' => $post->id,  // FIXED: Added post_id
                        'type' => 'like',
                        'data' => json_encode([
                            'reaction' => $request->type,
                            'post_content' => substr($post->content ?? 'Post', 0, 50)
                        ]),
                        'is_read' => false
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'likes_count' => $post->fresh()->likes_count
            ]);

        } catch (\Exception $e) {
            Log::error('Reaction error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to process reaction'], 500);
        }
    }

    /**
     * Add a comment
     */
    public function addComment(Request $request, Post $post)
    {
        try {
            $request->validate(['content' => 'required|string|max:1000']);

            $comment = Comment::create([
                'user_id' => Auth::id(),
                'post_id' => $post->id,
                'content' => $request->content
            ]);

            $post->increment('comments_count');

            // Create notification for post owner
            if ($post->user_id !== Auth::id()) {
                Notification::create([
                    'user_id' => $post->user_id,
                    'from_user_id' => Auth::id(),
                    'post_id' => $post->id,  // FIXED: Added post_id
                    'comment_id' => $comment->id,
                    'type' => 'comment',
                    'data' => json_encode([
                        'comment' => $request->content,
                        'post_content' => substr($post->content ?? 'Post', 0, 50)
                    ]),
                    'is_read' => false
                ]);
            }

            return response()->json([
                'success' => true,
                'comment' => $comment->load('user'),
                'comments_count' => $post->fresh()->comments_count
            ]);

        } catch (\Exception $e) {
            Log::error('Comment error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to add comment'], 500);
        }
    }

    /**
     * Get reactions
     */
    public function getReactions(Post $post)
    {
        try {
            $reactions = $post->reactions()->with('user:id,name,avatar')->get()->groupBy('type');
            $summary = $post->reactions()
                ->select('type', \DB::raw('count(*) as count'))
                ->groupBy('type')
                ->pluck('count', 'type');

            return response()->json([
                'success' => true,
                'reactions' => $reactions,
                'summary' => $summary,
                'total' => $post->likes_count
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting reactions: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to get reactions'], 500);
        }
    }
}