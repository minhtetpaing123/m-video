<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PostCrudController extends Controller
{
    /**
     * Update the specified post
     */
    public function update(Request $request, Post $post)
    {
        try {
            // Check authorization
            if ($post->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to update this post.'
                ], 403);
            }

            // Validate request
            $request->validate([
                'content' => 'required|string|max:5000'
            ]);

            // Update post
            $post->content = $request->content;
            $post->save();

            return response()->json([
                'success' => true,
                'message' => 'Post updated successfully!',
                'post' => $post
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating post: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update post'
            ], 500);
        }
    }

    /**
     * Remove the specified post
     */
    public function destroy(Post $post)
    {
        try {
            if ($post->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            // Delete post
            $post->delete();

            return response()->json([
                'success' => true,
                'message' => 'Post deleted successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting post: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete post'
            ], 500);
        }
    }
}