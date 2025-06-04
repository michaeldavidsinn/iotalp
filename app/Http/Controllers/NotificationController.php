<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::orderBy('created_at', 'desc')->get();
        return response()->json($notifications);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'message' => 'nullable|string',
        ]);

        $notification = Notification::create([
            'title' => $request->title,
            'message' => $request->message,
        ]);

        return response()->json($notification, 201);
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->is_read = true;
        $notification->save();

        return response()->json(['message' => 'Notification marked as read']);
    }
}

