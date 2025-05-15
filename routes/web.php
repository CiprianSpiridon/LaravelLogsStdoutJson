<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestLogController;

Route::get('/', function () {
    return view('welcome');
});

// Add this route for testing logs
Route::get('/test-logs', [TestLogController::class, 'triggerLogs']);
