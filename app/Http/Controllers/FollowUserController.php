<?php

namespace App\Http\Controllers;

use App\Models\followUser;
use App\Http\Requests\StorefollowUserRequest;
use App\Http\Requests\UpdatefollowUserRequest;
use Illuminate\Support\Facades\Auth;

class FollowUserController extends Controller
{
    public function followUser($id)
    {
        $myId = Auth::user()->id;
        $userId = $id;

        if ($myId == $userId) {
            return response()->json(['success' => false, 'message' => 'You cannot follow yourself.']);
        }

        $exists = \App\Models\followUser::where('authorId', $userId)
            ->where('followerId', $myId)
            ->exists();

        if ($exists) {
            return response()->json(['success' => false, 'message' => 'You are already following this user.']);
        }

        try {
            $relation = new \App\Models\followUser;
            $relation->authorId = $userId;
            $relation->followerId = $myId;
            $relation->save();
            return response()->json(['success' => true, 'message' => "Follow user completed!"]);
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
