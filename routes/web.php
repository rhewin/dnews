<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use Inertia\Inertia;


Route::get('/', [ArticleController::class, 'index'])->name('article.index');
Route::get('/article/form', [ArticleController::class, 'form'])->name('article.form.create');
Route::get('/article/form/{id}', [ArticleController::class, 'form'])->name('article.form');
Route::get('/article/{id}', [ArticleController::class, 'show'])->name('article.show');
Route::delete('/article/{id}', [ArticleController::class, 'archived'])->name('article.archive');
Route::put('/article/{id}', [ArticleController::class, 'update'])->name('article.update');
Route::post('/article/create', [ArticleController::class, 'create'])->name('article.create');
Route::post('/article/{id}/like', [LikeController::class, 'like'])->name('article.like');
Route::post('/article/{id}/dislike', [LikeController::class, 'dislike'])->name('article.dislike');

Route::post('/comment/create', [CommentController::class, 'create'])->name('comment.create');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
