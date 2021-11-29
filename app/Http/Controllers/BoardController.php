<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Services\TSModelServer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class BoardController extends Controller
{

    public function getBoardPaths(): JsonResponse
    {
        //quick and easy request to grab all board routes for render
        return response()->json(Board::select('short_name')->pluck('short_name'));
    }

    public function listBoards(TSModelServer $tsapi)
    {
        return $tsapi->allowWith([
            'threads', 'threads.latestPosts.images', 'threads.posts', 'threads.firstPost.images',
            'threads.latestPosts.mentions', 'threads.firstPost.mentions'
        ])->respond(Board::class);
    }
}
