<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Http\Requests\StorecategoryRequest;
use App\Http\Requests\UpdatecategoryRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function categoryPage($id)
    {
        $allCategory = category::all();
        $bestAuthors = User::select('users.*')
            ->addSelect([
                'likes_count' => DB::table('likes')
                    ->join('posts', 'posts.id', '=', 'likes.post_id')
                    ->whereColumn('posts.authorId', 'users.id')
                    ->selectRaw('COUNT(*)')
            ])
            ->having('likes_count', '>', 0)
            ->orderByDesc('likes_count')
            ->limit(3)
            ->get();

        $category = category::findOrFail($id);
        $posts = Category::findOrFail($id)->posts()->paginate(5, ['*'], 'show-page');

        $posts->getCollection()->transform(function ($post) {
            $preview = '';
            $contentArr = json_decode($post->content, true);

            if (isset($contentArr['blocks'])) {
                foreach ($contentArr['blocks'] as $block) {
                    if (isset($block['data']['text'])) {
                        $preview .= strip_tags($block['data']['text']) . ' ';
                    }
                }
                $preview = Str::limit(trim($preview), 180);
            }

            $post->preview = $preview;
            return $post;
        });

        return view('categoryPage', compact('category', 'posts', 'bestAuthors', 'allCategory'));
    }
}
