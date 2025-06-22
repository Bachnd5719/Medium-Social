<?php

use App\Http\Controllers\EmotionController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [PostController::class, 'index'])->name('dashboard');
Route::get('/@{user:username}', [PublicProfileController::class,'show'])->name('profile.show');
Route::get('/posts/{post}/emotions-count', [EmotionController::class, 'getEmotionCounts'])->name('getEmotion');
Route::get('/@{username}/{post:slug}', [PostController::class,'show'])->name('post.show');
Route::get('/category/{category}', [PostController::class,'category'])->name('post.byCategory');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/post/create', [PostController::class, 'create'])->name('post.create');
    Route::get('/my-posts', [PostController::class, 'myPosts'])->name('myPosts');
    Route::get('/post/{post:slug}', [PostController::class, 'edit'])->name('post.edit');
    Route::delete('/post/{post}', [PostController::class, 'destroy'])->name('post.destroy');
    Route::put('/post/{post}', [PostController::class, 'update'])->name('post.update');
    Route::post('/post', [PostController::class, 'store'])->name('post.store');
    Route::post('/posts/{post}/emotion', [EmotionController::class, 'toggleEmotion'])->name('toggleEmotion');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/follow/{user}', [FollowerController::class,'follow'])->name('follow');
});

require __DIR__ . '/auth.php';
