<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

// Route::inertia('/', 'Home')->name('home');
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
