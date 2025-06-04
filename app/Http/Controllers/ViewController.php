<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\SystemStatus;

class ViewController extends Controller
{
    public function dashboard()
    {
        $notifications = Notification::latest()->get();
        $status = SystemStatus::first();
        return view('dashboard', compact('notifications', 'status'));
    }
}
