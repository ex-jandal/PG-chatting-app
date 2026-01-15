<?php

use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/chat/{id?}', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/create', [ChatController::class, 'create'])->name('chat.create');
    Route::post('/chat/{id}/send', [ChatController::class, 'store'])->name('chat.store');
});
require __DIR__ . '/settings.php';
require __DIR__ . '/admin.php';
