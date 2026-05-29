<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController; // Jangan lupa import ini
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Tambahin route kategori di sini
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);
    Route::resource('products', ProductController::class); // Tambahin ini man!
    Route::resource('products', \App\Http\Controllers\ProductController::class);
});

require __DIR__.'/auth.php';
