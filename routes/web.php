<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FotoController;
use App\Http\Controllers\MainMenuController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('mainmenu');
})->middleware('redirectIfAuthenticated');

Route::get('/signin', function() {
    return view('auth.login');
})->middleware('autologout');

Auth::routes();
Route::middleware(['auth'])->group(function() {
    Route::prefix('gallery')->group(function () {   
        Route::get('/', [FotoController::class, 'index'])->name('gallery');
        Route::get('/create', [FotoController::class, 'create'])->name('create.foto');
        Route::get('/upload', [FotoController::class, 'upload'])->name('upload.image');
    });
    
    Route::prefix('album')->group(function() {
        Route::get('/', [AlbumController::class, 'index'])->name('album');
        Route::get('/create', [AlbumController::class, 'create'])->name('create.album');
    });
    
    Route::prefix('profile')->group(function() {
        Route::get('/', [ProfileController::class, 'index'])->name('profile');
    });

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

});
