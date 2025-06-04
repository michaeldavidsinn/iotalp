<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemStatus;

class SystemStatusController extends Controller
{
    // Tampilkan status sistem
    public function index()
    {
        $status = SystemStatus::first();
        return response()->json($status);
    }

    // Ubah status sistem (nyala/mati)
    public function updateSystemStatus(Request $request)
{
    $request->validate([
        'is_on' => 'required|boolean',
    ]);

    $status = SystemStatus::first();
    if (!$status) {
        $status = new SystemStatus();
    }

    $status->is_on = $request->is_on;
    $status->save();

    return response()->json(['success' => true, 'is_on' => $status->is_on]);
}
}
