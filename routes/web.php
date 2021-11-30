<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\ThreadController;
use App\Services\Prefetcher;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('generate.tokens')->group(function () {
    Route::get('/', fn (Prefetcher $prefetch) => view('spa')->with($prefetch->get()))->name('view:app');
    Route::get('/{board}', fn (Prefetcher $prefetch) => view('spa')->with($prefetch->get()))->name('view:board');
    Route::get('/{board}/{thread}', fn (Prefetcher $prefetch) => view('spa')->with($prefetch->get()))->name('view:thread');
});

Route::get('/super/test/ok/dude', function() {
    \App\Events\NewReply::dispatch(\App\Models\Post::first());
});
