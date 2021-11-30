<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\TokenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('internal/v1/')->group(function() {
    Route::prefix('model')->group(function () { //POST vs GET: filtering via TSModelServer. Check frontend/Server
        Route::post('thread/list', [ThreadController::class, 'listThreads'])->name('list:threads');
        Route::post('posts/list', [ThreadController::class, 'listPosts'])->name('list:posts');
        Route::post('boards/list', [BoardController::class, 'listBoards'])->name('list:boards');
    });

    Route::get('/list/boards', [BoardController::class, 'getBoardPaths']);
    Route::post('/token/apply', [TokenController::class, 'apply'])->name('apply:token');

    Route::prefix('thread')->group(function() {
        Route::post('subscribe', [TokenController::class, 'subscribe'])->name('token:subscribe');
        Route::post('unsubscribe', [TokenController::class, 'unsubscribe'])->name('token:unsubscribe');
    });

    Route::prefix('create')->group(function() {
        Route::post('thread', [ThreadController::class, 'createThread'])->name('create:thread');
        Route::post('post', [ThreadController::class, 'createPost'])->name('create:post');
    });
});
