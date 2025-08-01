<?php

namespace App\Http\Controllers;

use App\Models\like;
use App\Http\Requests\StorelikeRequest;
use App\Http\Requests\UpdatelikeRequest;
use App\Models\post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class LikeController extends Controller
{
    public function countLike($id){
        try {
            $likeCount = like::where('post_id', $id)->where('like', true)->count();
            $dislikeCount = like::where('post_id', $id)->where('like', false)->count();

            return response()->json([
                'success' => true,
                'likeCount' => $likeCount,
                'dislikeCount' => $dislikeCount
            ]);
        } catch (\Throwable $e) {
            Log::error('Count Like Failed: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => 'Count Like Fail']);
        }
    }

    public function like(Request $request){
        DB::beginTransaction();
        try{
            $post_id = $request->post_id;
            $user_id = Auth::id();
            // Check if user already liked/disliked this post
            $existingDisLike = like::where([['post_id', '=', $post_id],['user_id', "=", $user_id],['like', "=", false]])->first();
            $existingLike = like::where([['post_id', '=', $post_id],['user_id', "=", $user_id],['like', "=", true]])->first();

            if($existingDisLike){
                $existingDisLike->like = true;
                $existingDisLike->save();
            } 
            else if($existingLike){
                $existingLike->delete();
            }
            else {
                $newlike = new like();
                $newlike->post_id = $post_id;
                $newlike->user_id = $user_id;
                $newlike->like = true;
                $newlike->save();
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Liked successfully']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Like Post Failed: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => 'Like Post Fail']);
        }
    }

    public function dislike(Request $request){
        DB::beginTransaction();
        try{
            $post_id = $request->post_id;
            $user_id = Auth::id();

            $existingDisLike = like::where([['post_id', '=', $post_id],['user_id', "=", $user_id],['like', "=", false]])->first();
            $existingLike = like::where([['post_id', '=', $post_id],['user_id', "=", $user_id],['like', "=", true]])->first();

            if($existingLike){
                $existingLike->like = false;
                $existingLike->save();
            }
            else if($existingDisLike){
                $existingDisLike->delete();
            }
            else {
                $newlike = new like();
                $newlike->post_id = $post_id;
                $newlike->user_id = $user_id;
                $newlike->like = false;
                $newlike->save();
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Disliked successfully']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Dislike Post Failed: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => 'Dislike Post Fail']);
        }
    }

    public function deleteLike(){

    }
}
