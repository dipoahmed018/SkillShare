<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AppNotificationsController extends Controller
{
    public function index(Request $request, User $user)
    {
        $notifications = $user->notifications;
        return response()->json($notifications, 200);
    }

    public function delete(Request $request, $id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $id)->first();
        $notification->delete();
        return response()->json($notification);
    }
}
