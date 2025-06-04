<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SystemStatusController;
use App\Http\Controllers\ViewController;


Route::get('/', [ViewController::class, 'dashboard']);

Route::get('/notifications', [NotificationController::class, 'index']);
Route::post('/notifications', [NotificationController::class, 'store']);
Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);

Route::get('/system-status', [SystemStatusController::class, 'index']);
Route::post('/api/system-status', [SystemStatusController::class, 'updateSystemStatus']);
