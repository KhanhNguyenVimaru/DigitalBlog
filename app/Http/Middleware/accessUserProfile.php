<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Models\followUser;
use Illuminate\Support\Facades\Auth;


class accessUserProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = User::find($request->route('id'));
        if (!$user || $user->banned) {
            return response()->view('404', [], 404);
        }
        $pagePrivacy = $user->privacy;
        $authorId = $user->id;
        $followerId = Auth::user()->id;

        $alreadyFollowed = followUser::where('authorId', $authorId)
            ->where('followerId', $followerId)
            ->exists();
        if ($alreadyFollowed) {
            $request->merge(['already_followed' => true]);
        }

        if ($pagePrivacy === 'private' && !$alreadyFollowed && $authorId !== $followerId) {
            $request->merge(['private_profile' => true]);
        }
        return $next($request);
    }
}
