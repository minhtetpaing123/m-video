<?php
// app/Http/Controllers/InteractionController.php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Reaction;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InteractionController extends Controller
{
    /**
     * Like/Unlike a post
     */
    public function toggleLike(Post $post)
    {
        try {
            $userId = Auth::id();
            
            $reaction = Reaction::where('user_id', $userId)
                ->where('post_id', $post->id)
                ->first();

            if ($reaction) {
                // Unlike
                $reaction->delete();
                $liked = false;
            } else {
                // Like
                Reaction::create([
                    'user_id' => $userId,
                    'post_id' => $post->id,
                    'type' => 'like'
                ]);
                $liked = true;

                // Create notification for post owner
                if ($post->user_id !== $userId) {
                    Notification::create([
                        'user_id' => $post->user_id,
                        'from_user_id' => $userId,
                        'post_id' => $post->id,
                        'type' => 'like',
                        'data' => json_encode([
                            'reaction' => 'like',
                            'post_content' => substr($post->content ?? 'Post', 0, 50)
                        ]),
                        'is_read' => false
                    ]);
                }
            }

            $post->loadCount('reactions as likes_count');

            return response()->json([
                'success' => true,
                'liked' => $liked,
                'likes_count' => $post->likes_count
            ]);

        } catch (\Exception $e) {
            Log::error('Toggle like error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to process like'
            ], 500);
        }
    }

    /**
     * Check if user liked a post
     */
    public function checkLike(Post $post)
    {
        try {
            return response()->json([
                'success' => true,
                'liked' => $post->isLikedBy(Auth::id())
            ]);
        } catch (\Exception $e) {
            Log::error('Check like error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to check like'
            ], 500);
        }
    }

    /**
     * Add comment to a post
     */
    public function addComment(Request $request, Post $post)
    {
        try {
            $request->validate([
                'content' => 'required|string|max:1000'
            ]);

            $comment = Comment::create([
                'user_id' => Auth::id(),
                'post_id' => $post->id,
                'content' => $request->content
            ]);

            $comment->load('user');

            $post->loadCount('comments as comments_count');

            // Create notification for post owner
            if ($post->user_id !== Auth::id()) {
                Notification::create([
                    'user_id' => $post->user_id,
                    'from_user_id' => Auth::id(),
                    'post_id' => $post->id,
                    'comment_id' => $comment->id,
                    'type' => 'comment',
                    'data' => json_encode([
                        'comment' => substr($request->content, 0, 50),
                        'post_content' => substr($post->content ?? 'Post', 0, 50)
                    ]),
                    'is_read' => false
                ]);
            }

            return response()->json([
                'success' => true,
                'comment' => [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'created_at' => $comment->created_at->diffForHumans(),
                    'user' => [
                        'name' => $comment->user->name,
                        'avatar' => $comment->user->profile_photo ?? null
                    ]
                ],
                'comments_count' => $post->comments_count
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Add comment error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add comment'
            ], 500);
        }
    }

    /**
     * Get all comments for a post
     */
    public function getComments(Post $post)
    {
        try {
            $comments = $post->comments()
                ->with('user')
                ->latest()
                ->get()
                ->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'content' => $comment->content,
                        'created_at' => $comment->created_at->diffForHumans(),
                        'user' => [
                            'name' => $comment->user->name,
                            'avatar' => $comment->user->profile_photo ?? null
                        ]
                    ];
                });
            
            return response()->json([
                'success' => true,
                'comments' => $comments
            ]);

        } catch (\Exception $e) {
            Log::error('Get comments error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get comments'
            ], 500);
        }
    }

    /**
     * Delete a comment
     */
    public function deleteComment(Comment $comment)
    {
        try {
            if ($comment->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $postId = $comment->post_id;
            $comment->delete();

            $post = Post::find($postId);
            $post->loadCount('comments as comments_count');

            return response()->json([
                'success' => true,
                'comments_count' => $post->comments_count
            ]);

        } catch (\Exception $e) {
            Log::error('Delete comment error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete comment'
            ], 500);
        }
    }

    /**
     * Share a post
     */
    public function share(Post $post)
    {
        try {
            $post->increment('shares_count');
            
            // Create notification for post owner
            if ($post->user_id !== Auth::id()) {
                Notification::create([
                    'user_id' => $post->user_id,
                    'from_user_id' => Auth::id(),
                    'post_id' => $post->id,
                    'type' => 'share',
                    'data' => json_encode([
                        'post_content' => substr($post->content ?? 'Post', 0, 50)
                    ]),
                    'is_read' => false
                ]);
            }

            return response()->json([
                'success' => true,
                'shares_count' => $post->shares_count
            ]);

        } catch (\Exception $e) {
            Log::error('Share error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to share'
            ], 500);
        }
    }

    /**
     * Get reaction summary for a post
     */
    public function getReactions(Post $post)
    {
        try {
            return response()->json([
                'success' => true,
                'summary' => $post->reaction_summary,
                'total' => $post->likes_count
            ]);

        } catch (\Exception $e) {
            Log::error('Get reactions error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get reactions'
            ], 500);
        }
    }
}