<?php

use App\Http\Controllers\ProfileController;
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


    Route::get('/private',[\App\Http\Controllers\ExchangeController::class, 'index'])->name('private');
    Route::get('/show/',[\App\Http\Controllers\ExchangeController::class, 'show'])->name('show');
    Route::post('/upload',[\App\Http\Controllers\FileUploadController::class,'upload'])->name('upload');

    Route::get('/captcha', [\App\Http\Controllers\FileUploadController::class, 'show'])->name('captcha');
    Route::post('verify-captcha', [\App\Http\Controllers\CaptchaController::class, 'verify'])->name('verify-captcha');
});

require __DIR__.'/auth.php';
