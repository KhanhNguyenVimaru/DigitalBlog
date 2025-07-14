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
        $pagePrivacy = User::findOrFail($request->route('id'))->privacy;
        if(Auth::user()->id === $request->route('id')){
            return redirect()->route('myProfile');
        }

        if ($pagePrivacy === 'private') {

            $authorId = $request->route('id');
            $followerId = Auth::user()->id;

            $relationExits = followUser::where('authorId', $authorId)
                ->where('followerId', $followerId)
                ->exists();
            if (!$relationExits && $authorId !== $followerId) {
                $request->merge(['private_profile' => true]);
            }
        }
        return $next($request);
    }
}
