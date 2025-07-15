<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Models\followUser;
use Illuminate\Support\Facades\Auth;
use App\Models\followRequest;


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

        // Kiểm tra follow request
        $requestSent = followRequest::where('followedId', $authorId)
            ->where('userId_request', $followerId)
            ->exists();
        if ($requestSent && !$alreadyFollowed) {
            $request->merge(['request_sent' => true]);
        }
        // Có thể mở rộng: kiểm tra trạng thái request (nếu có cột status), ở đây chỉ check tồn tại
        // Nếu muốn cho gửi lại request, cần có logic xác định (ví dụ: request bị từ chối hoặc đã bị xóa)
        // $canRequestAgain = ...
        // $request->merge(['can_request_again' => true]);

        if ($pagePrivacy === 'private' && !$alreadyFollowed && $authorId !== $followerId) {
            $request->merge(['private_profile' => true]);
        }
        return $next($request);
    }
}
