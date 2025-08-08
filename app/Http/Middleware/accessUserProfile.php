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

        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $user = User::find($request->route('id'));
        if (!$user) {
            // sau này bổ sung return 404 khi ban
            return response()->view('404', [], 404);
        }
        if($user->id === Auth::id()) {
            return redirect()->route('myProfile');
        }
        $pagePrivacy = $user->privacy;
        $authorId = $user->id;
        $followerId = Auth::user()->id;

        $alreadyFollowed = followUser::where('authorId', $authorId)
            ->where('followerId', $followerId)
            ->where('banned', false) // không bị ban
            ->orWhere('banned', null)
            ->exists();

        $ban = followUser::where('authorId', $authorId) // user là người chủ động ban
            ->where('followerId', $followerId)
            ->where('banned', true)
            ->exists();

        $banned = followUser::where('authorId', $followerId) // user là người bị ban
            ->where('followerId', $authorId)
            ->where('banned', true)
            ->exists();
        // xử lý các case chặn
        if ($ban) {
            $request->merge(['ban' => true]);
        }
        if($banned) {
            return response()->view('notify.accountNotExisted', ['error' => 'Oops! This account is not available.'], 404);
        }

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
