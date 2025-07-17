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
    public function loadLink($request)
    {
        $url = $request->input('url') ?? $request->query('url');
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return response()->json(['success' => 0, 'message' => 'Invalid URL']);
        }
        try {
            $html = @file_get_contents($url);
            if (!$html) {
                return response()->json(['success' => 0, 'message' => 'Cannot fetch URL']);
            }
            preg_match('/<title>(.*?)<\\/title>/si', $html, $title);
            preg_match('/<meta name="description" content="(.*?)"/si', $html, $desc);
            preg_match('/<meta property="og:image" content="(.*?)"/si', $html, $img);
            return response()->json([
                'success' => 1,
                'meta' => [
                    'title' => $title[1] ?? $url,
                    'description' => $desc[1] ?? '',
                    'image' => $img[1] ?? '',
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => 0, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function updateStatus($id)
    {
        $post = post::findOrFail($id);
        if ($post->authorId !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $post->status = $post->status === 'public' ? 'private' : 'public';
        $post->save();

        return response()->json(['success' => true, 'message' => 'Post status updated successfully', 'status' => $post->status]);
    }
    /**
     * Display a listing of the resource.
     */
    public function deletePost($id)
    {
        $post = post::findOrFail($id);
        if ($post->authorId !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized']);
        }
        DB::beginTransaction();
        try {
            // Xóa nội dung dài nếu có
            if ($post->long_content()->exists()) {
                $post->long_content()->delete();
            }
            // Xóa bài viết
            $post->delete();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Post deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete post', 'message' => $e->getMessage()]);
        }
    }

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
                'url' => Storage::url($path)
            ]
        ], 200);
    }

    public function storeContent(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|in:public,private',
            'categoryId' => 'nullable|exists:categories,id',
            'groupId' => 'nullable|exists:groups,id',
            'content' => 'required|string',
            'coverImage' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Chỉ cho phép ảnh, tối đa 2MB
        ]);
        $exists = post::where('title', $validated['title'])
            ->where('authorId', Auth::id())
            ->exists();

        if ($exists) {
            return response()->json(['error' => 'Post with this title already exists']);
        }

        $userId = Auth::id();
        $content = $request->input('content');

        // Tính byte size của content (EditorJS JSON dạng string)
        $contentSize = strlen($content);

        DB::beginTransaction();

        try {
            // Lưu ảnh đại diện nếu có
            if ($request->hasFile('coverImage')) {
                $file = $request->file('coverImage');
                $path = $file->store('uploads', 'public');
                $validated['additionFile'] = Storage::url($path); // Lưu đường dẫn vào additionFile
            }

            $post = new Post();
            $post->title = $validated['title'];
            $post->status = $validated['status'];
            $post->categoryId = $validated['categoryId'] ?? null;
            $post->groupId = $validated['groupId'] ?? null;
            $post->authorId = $userId;
            $post->additionFile = $validated['additionFile'] ?? null; // additionFile là ảnh đại diện

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

            return response()->json([
                'success' => true,
                'message' => 'Post created successfully',
                'postId' => $post->id
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => 'Failed to create post',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function contentOfUsers(Request $request)
    {
        $userId = Auth::id();
        $posts = post::where('authorId', $userId)
            ->with(['category', 'long_content'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($posts);
    }

    public function contentOfAuthor($id)
    {
        $posts = post::where('authorId', $id)
            ->where('status', 'public') // Chỉ lấy bài viết công kha
            ->with(['category', 'long_content'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($posts);
    }

    /**
     * Xem nội dung post dạng JSON EditorJS
     */
    public function viewContentJson($id)
    {
        $post = post::with('category', 'author')->findOrFail($id);
        return view('post_content_viewer', [
            'content' => $post->content,
            'title' => $post->title,
            'category' => $post->category ? $post->category->content : null,
            'category_id' => $post->category ? $post->category->id : null,
            'author_avatar' => $post->author && $post->author->avatar ? $post->author->avatar : 'https://static.vecteezy.com/system/resources/previews/009/292/244/non_2x/default-avatar-icon-of-social-media-user-vector.jpg',
            'author_name' => $post->author ? $post->author->name : 'Unknown',
            'created_at' => $post->created_at->format('Y-m-d') ? $post->created_at->format('Y-m-d') : 'Unknown',
        ]);
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
