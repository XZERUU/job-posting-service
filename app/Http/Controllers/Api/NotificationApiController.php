<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationApiController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'notifications' => [],
            'unread_count' => 0,
        ]);
    }

    public function markRead(Request $request, $id)
    {
        return response()->json([
            'message' => 'Notification marked as read.',
        ]);
    }
}
