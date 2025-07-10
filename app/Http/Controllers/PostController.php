<?php

namespace App\Http\Controllers;

use App\Models\post;
use App\Http\Requests\StorepostRequest;
use App\Http\Requests\UpdatepostRequest;
use Illuminate\Http\Request;
use App\Models\long_content;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function uploadFile(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048' // Chỉ cho phép ảnh, tối đa 2MB
        ]);

        $file = $request->file('image');
        $path = $file->store('uploads', 'public');
        return response()->json([
            'success' => 1,
            'file' => [
                'url' => Storage::url($path) // đúng: trả về /storage/uploads/abc.png
            ]
        ], 200);
    }

    public function storeContent(Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|in:public,private',
            'categoryId' => 'nullable|exists:categories,id',
            'groupId' => 'nullable|exists:groups,id',
            'content' => 'required|string',
        ]);

        $userId = Auth::id();
        $content = $request->input('content');

        // Tính byte size của content (EditorJS JSON dạng string)
        $contentSize = strlen($content);

        DB::beginTransaction();

        try {
            $post = new Post();
            $post->status = $validated['status'];
            $post->categoryId = $validated['categoryId'] ?? null;
            $post->groupId = $validated['groupId'] ?? null;
            $post->authorId = $userId;

            // Nếu content <= 65535 bytes ➝ lưu vào posts.content
            if ($contentSize <= 65535) {
                $post->content = $content;
            }

            $post->save();

            // Nếu content lớn ➝ lưu vào bảng phụ long_contents
            if ($contentSize > 65535) {
                long_content::create([
                    'postId' => $post->id,
                    'content' => $content,
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Post created successfully', 'postId' => $post->id], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create post', 'message' => $e->getMessage()], 500);
        }
    }

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
    public function store(StorepostRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatepostRequest $request, post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(post $post)
    {
        //
    }
}
