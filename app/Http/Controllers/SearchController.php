<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\post;
use App\Models\User;
use App\Models\category;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        $posts = post::where('content', 'like', "%$query%")
            ->orWhere('title', 'like', "%$query%")
            ->get();
        $users = User::where('name', 'like', "%$query%")
            ->orWhere('email', 'like', "%$query%")
            ->get();
        $categories = category::where('content', 'like', "%$query%")
            ->get();

        return view('search', compact('query', 'posts', 'users', 'categories'));
    }
} 