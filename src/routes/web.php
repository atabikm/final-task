<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/chat', [ChatController::class, 'index']);
    Route::post('/chat', [ChatController::class, 'store'])->name('chat.store');
});

Route::get('/', function () {
    return redirect('/chat');
});