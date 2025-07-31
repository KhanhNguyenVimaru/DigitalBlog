<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class CommentController extends Controller
{
    /**
     * Store a newly created comment
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'post_id' => 'required|exists:posts,id',
                'comment' => 'required|max:2048',
            ]);

            $comment = new Comment();
            $comment->post_id = $validated['post_id'];
            $comment->user_id = Auth::id();
            $comment->comment = $validated['comment'];
            $comment->save();

            return response()->json([
                'success' => true,
                'message' => 'Comment posted successfully!',
                'comment' => $comment->load('user')
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to post comment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a comment
     */
    public function destroy($id)
    {
        try {
            $comment = Comment::findOrFail($id);
            
            // Check if user owns the comment
            if ($comment->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You can only delete your own comments'
                ], 403);
            }

            $comment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Comment deleted successfully!'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete comment: ' . $e->getMessage()
            ], 500);
        }
    }
}
