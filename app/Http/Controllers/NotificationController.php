<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    // Get all notifications (or filtered by type)
    public function index(Request $request)
    {
        $query = Notification::orderBy('created_at', 'desc');

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        return response()->json($query->get());
    }

    // Store a new notification
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'message' => 'nullable|string',
            'type' => 'nullable|string',
            'important' => 'boolean',
            'data' => 'nullable|array',
        ]);

        $notification = Notification::create([
            'title' => $validated['title'],
            'message' => $validated['message'] ?? null,
            'type' => $validated['type'] ?? null,
            'important' => $validated['important'] ?? false,
            'data' => isset($validated['data']) ? json_encode($validated['data']) : null,
        ]);

        return response()->json($notification, 201);
    }

    // Mark notification as read
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->is_read = true;
        $notification->save();

        return response()->json(['message' => 'Notification marked as read']);
    }
}
