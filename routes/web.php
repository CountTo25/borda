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

Route::get('/super/test/ok/buddy', function(\App\Services\Dactyloscopy $dactyloscopy) {
    return $dactyloscopy->make();
});

Route::get('/', fn (Prefetcher $prefetch) => view('spa')->with($prefetch->get()))->name('view:app');
Route::get('/{board}', fn (Prefetcher $prefetch) => view('spa')->with($prefetch->get()))->name('view:board');
Route::get('/{board}/{thread}', fn (Prefetcher $prefetch) => view('spa')->with($prefetch->get()))->name('view:thread');
