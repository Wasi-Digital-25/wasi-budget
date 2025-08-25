<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\QuotePdfController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth','verified','scope.company'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('clients', ClientController::class);
    Route::resource('quotes', QuoteController::class);
    Route::post('quotes/{quote}/send', [QuoteController::class,'send'])->name('quotes.send');
    Route::post('quotes/{quote}/accept', [QuoteController::class,'accept'])->name('quotes.accept');
    Route::post('quotes/{quote}/reject', [QuoteController::class,'reject'])->name('quotes.reject');
    Route::get('quotes/{quote}/pdf', [QuotePdfController::class,'download'])->name('quotes.pdf');
});

require __DIR__.'/auth.php';
