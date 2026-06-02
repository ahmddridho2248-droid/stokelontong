<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController; // Jangan lupa import ini
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);
    Route::resource('products', \App\Http\Controllers\ProductController::class);
    
    // Transactions In
    Route::get('/transactions/in', [\App\Http\Controllers\TransactionController::class, 'createIn'])->name('transactions.in.create');
    Route::post('/transactions/in', [\App\Http\Controllers\TransactionController::class, 'storeIn'])->name('transactions.in.store');
    
    // Transactions Out
    Route::get('/transactions/out', [\App\Http\Controllers\TransactionController::class, 'createOut'])->name('transactions.out.create');
    Route::post('/transactions/out', [\App\Http\Controllers\TransactionController::class, 'storeOut'])->name('transactions.out.store');
    
    Route::resource('transactions', \App\Http\Controllers\TransactionController::class);
});

require __DIR__.'/auth.php';
