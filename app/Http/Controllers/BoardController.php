<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Thread;
use App\Services\TSModelServer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function getBoardPaths(): JsonResponse
    {
        //quick and easy request to grab all board routes for render
        return response()->json(Board::select('short_name')->pluck('short_name'));
    }

    public function listBoards(TSModelServer $tsapi, Request $request)
    {
        return $tsapi->allowWith(['threads', 'threads.latestPosts.images', 'threads.posts', 'threads.firstPost.images'])
            ->respond(Board::class);
    }
}
