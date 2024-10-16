<?php

use App\Http\Controllers\API\V1\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Versioned routes
Route::prefix('v1')->group(function () {
    Route::post('/send-notification', [NotificationController::class, 'sendNotification']);
    Route::post('/send-multicast-notification', [NotificationController::class, 'sendMulticastNotification']);
});
