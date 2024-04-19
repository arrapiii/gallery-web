<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AvatarController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FotoController;
use App\Http\Controllers\KomentarFotoController;
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

Route::prefix('avatar')->group(function() {
    Route::get('/', [AvatarController::class, 'index'])->name('home');
    Route::post('/upload', [AvatarController::class, 'store'])->name('store.avatar');
    Route::post('/remove', [AvatarController::class, 'remove'])->name('remove.avatar');
});

Auth::routes();
Route::middleware(['auth'])->group(function() {
    Route::prefix('home')->group(function() {
        Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    });

    Route::prefix('gallery')->group(function () {
        Route::get('/', [FotoController::class, 'index'])->name('gallery');
        Route::get('/detail', [FotoController::class, 'detail'])->name('detail');
        Route::get('/create', [FotoController::class, 'create'])->name('create.foto');
        Route::post('/store', [FotoController::class, 'store'])->name('store.foto');
        Route::post('/photos/{foto}/like', [FotoController::class, 'like'])->name('photos.like');
        Route::delete('/photos/{foto}/unlike', [FotoController::class, 'unlike'])->name('photos.unlike');
        Route::get('/photos{foto}/checklike', [FotoController::class, 'checkLike'])->name('photos.checklike');
        Route::get('/photos/{foto}/get-like-count', [FotoController::class, 'getLikeCount'])->name('photos.getlikecount');
        Route::get('/photos/{foto}/comments', [FotoController::class, 'comments'])->name('photos.comments');
        Route::get('/photos/{foto}/edit', [FotoController::class, 'edit'])->name('edit.foto');
        Route::put('/photos/{foto}/update', [FotoController::class, 'update'])->name('update.foto');
        Route::delete('/photos/{foto}/destroy', [FotoController::class, 'destroy'])->name('delete.foto');
    });
    
    Route::prefix('album')->group(function() {
        Route::get('/', [AlbumController::class, 'index'])->name('album');
        Route::get('/create', [AlbumController::class, 'create'])->name('create.album');
        Route::post('/store', [AlbumController::class, 'store'])->name('store.album');
        Route::post('/albums/export/{album}', [AlbumController::class, 'export'])->name('export.album');

    });
    
    Route::prefix('profile')->group(function() {
        Route::get('/', [ProfileController::class, 'index'])->name('profile');
        Route::get('/albums/{id}', [AlbumController::class, 'show'])->name('album.show');
    });

    Route::prefix('comment')->group(function() {
        Route::post('/', [KomentarFotoController::class, 'store'])->name('comments.store');
    });

});
