<?php

namespace App\Http\Controllers;

use App\Models\Notify;
use App\Http\Requests\StoreNotifyRequest;
use App\Http\Requests\UpdateNotifyRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class NotifyController extends Controller

{
    public function deleteNotify(Request $request)
    {
        try {
            $deleteNotify = Notify::where('id', $request->id)->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function loadUserNotify()
    {
        $notifies = Notify::where('send_to_id', Auth::id())
            ->orderBy('created_at', 'desc')->limit(6)->get();
            return response()->json($notifies);
    }
}
