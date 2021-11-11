<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\ThreadController;
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
        Route::post('thread/list', [ThreadController::class, 'listThreads']);
        Route::post('posts/list', [ThreadController::class, 'listPosts']);
        Route::post('boards/list', [BoardController::class, 'listBoards']);
    });

    Route::get('/list/boards', [BoardController::class, 'getBoardPaths']);

    Route::prefix('create')->group(function() {
        Route::post('thread', [ThreadController::class, 'createThread']);
        Route::post('post', [ThreadController::class, 'createPost']);
    });
});
