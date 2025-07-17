<?php

namespace App\Http\Controllers;

use App\Models\followUser;
use App\Http\Requests\StorefollowUserRequest;
use App\Http\Requests\UpdatefollowUserRequest;
use App\Models\User;
use App\Models\followRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Notify;
use Illuminate\Http\Request;


class FollowUserController extends Controller
{
   public function acceptRequest(Request $request)
    {
        try {
            $my_id = Auth::id();
            $send_from_id = $request->send_from_id;

            $request_accepted = new followUser();
            $request_accepted->authorId = $my_id;
            $request_accepted->followerId = $send_from_id;
            $request_accepted->save();

            $get_accpeted = new Notify();
            $get_accpeted->send_from_id = $my_id;
            $get_accpeted->send_to_id = $send_from_id;
            $get_accpeted->type = "accepted";
            $get_accpeted->notify_content = Auth::user()->name . " has accpeted your request";
            $get_accpeted->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function denyRequest(Request $request)
    {
        $send_from_id = $request->send_from_id;
        $notify_id = $request->id;
        try {
            $remove_request = followRequest::where('followedId', Auth::id())->where('userId_request', $send_from_id)->delete();
            $remove_notify = Notify::where('id', $notify_id)->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function deleteFollow($id)
    {
        $myId = Auth::user()->id;
        try {
            $deleted = followUser::where('authorId', $id)
                ->where('followerId', $myId)
                ->delete();
            if ($deleted) {
                return response()->json(['success' => true, 'message' => 'Unfollowed successfully.']);
            } else {
                return response()->json(['success' => false, 'message' => 'No follow relationship found.']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function followUser($id)
    {
        $myId = Auth::user()->id;
        $userId = $id;

        // Không tự follow chính mình
        if ($myId == $userId) {
            return response()->json(['success' => false, 'message' => 'You cannot follow yourself.']);
        }

        // đã follow ai đó từ trước
        $exists = \App\Models\followUser::where('authorId', $userId)
            ->where('followerId', $myId)
            ->exists();
        if ($exists) {
            return response()->json(['type' => 'following', 'success' => false, 'message' => 'You are already following this user.']);
        }

        $authorPrivacy = User::where('id', $id)->value('privacy');
        if ($authorPrivacy === "private") {
            // Kiểm tra đã gửi request chưa
            $requestExists = followRequest::where('followedId', $userId)
                ->where('userId_request', $myId)
                ->exists();
            if ($requestExists) {
                return response()->json(['type' => 'sent_request', 'success' => false, 'message' => 'Request already sent']);
            }
            try {
                $queue = new followRequest();
                $queue->followedId = $userId;
                $queue->userId_request = $myId;
                $queue->save();

                $notify = new Notify();
                $notify->send_from_id = $myId;
                $notify->send_to_id = $userId;
                $notify->type = 'follow_request';
                $username = User::where('id', $myId)->value('name');
                $notify->notify_content = $username . ' has sent you a follow request';
                $notify->save();

                return response()->json(['type' => 'request', 'success' => true, 'message' => "Request has been sent"]);
            } catch (\Exception $e) {
                return response()->json(['type' => 'request', 'success' => false, 'message' => 'Error: ' . $e->getMessage()]);
            }
        } else {
            try {
                $relation = new followUser;
                $relation->authorId = $userId;
                $relation->followerId = $myId;
                $relation->save();

                $notify = new Notify();
                $notify->send_from_id = $myId;
                $notify->send_to_id = $userId;
                $notify->type = 'follow';
                $username = User::where('id', $myId)->value('name');
                $notify->notify_content = $username . ' is following you';
                $notify->save();

                return response()->json(['type' => 'follow', 'success' => true, 'message' => "Follow user completed!"]);
            } catch (\Exception $e) {
                return response()->json(['type' => 'follow', 'success' => false, 'message' => 'Error: ' . $e->getMessage()]);
            }
        }
    }

    public function revokeFollowRequest($id)
    {
        $myId = Auth::user()->id;
        try {
            $deleted = \App\Models\followRequest::where('followedId', $id)
                ->where('userId_request', $myId)
                ->delete();
            if ($deleted) {
                return response()->json(['success' => true, 'message' => 'Request revoked successfully.']);
            } else {
                return response()->json(['success' => false, 'message' => 'No follow request found.']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorefollowUserRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(followUser $followUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(followUser $followUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatefollowUserRequest $request, followUser $followUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(followUser $followUser)
    {
        //
    }
}
