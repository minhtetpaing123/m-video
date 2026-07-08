<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    /**
     * Store a newly created comment
     */
    public function store(Request $request, Post $post)
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

            $post->increment('comments_count');

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'comment' => $comment->load('user'),
                    'comments_count' => $post->fresh()->comments_count
                ]);
            }

            return redirect()->back()->with('success', 'Comment added successfully!');

        } catch (\Exception $e) {
            Log::error('Error adding comment: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to add comment'], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to add comment.');
        }
    }

    /**
     * Update the specified comment
     */
    public function update(Request $request, Comment $comment)
    {
        try {
            if ($comment->user_id !== Auth::id()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $request->validate([
                'content' => 'required|string|max:1000'
            ]);

            $comment->update([
                'content' => $request->content
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'comment' => $comment->load('user')
                ]);
            }

            return redirect()->back()->with('success', 'Comment updated successfully!');

        } catch (\Exception $e) {
            Log::error('Error updating comment: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to update comment'], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to update comment.');
        }
    }

    /**
     * Remove the specified comment
     */
    public function destroy(Comment $comment)
    {
        try {
            if ($comment->user_id !== Auth::id()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $post = $comment->post;
            $comment->delete();
            $post->decrement('comments_count');

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'comments_count' => $post->fresh()->comments_count
                ]);
            }

            return redirect()->back()->with('success', 'Comment deleted successfully!');

        } catch (\Exception $e) {
            Log::error('Error deleting comment: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to delete comment'], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to delete comment.');
        }
    }

    /**
     * Get comments for a post (for AJAX loading)
     */
    public function index(Post $post)
    {
        try {
            $comments = $post->comments()
                ->with('user')
                ->latest()
                ->paginate(10);

            return response()->json([
                'success' => true,
                'comments' => $comments
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading comments: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to load comments'], 500);
        }
    }
}