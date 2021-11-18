<?php

namespace App\Services;

use App\Models\Board;
use App\Models\Thread;
use Illuminate\Http\Request;

class Prefetcher
{
    public function get(): array
    {
        /** @var Request $request */
        $request = request();
        /** @var TSModelServer $ts */
        $ts = app(TSModelServer::class);

        $board = $request->route('board');
        $thread = $request->route('thread');

        $package = [];
        $package['boards'] = Board::select('short_name')->pluck('short_name');

        if (
            $board !== null
            && $boardModel = Board::with(['threads.latestPosts', 'threads.posts', 'threads.firstPost'])
                ->firstWhere('short_name', $board)
        ) {
            $package['board'] = $boardModel->toArray();
        }

        if ($thread !== null && $threadModel = Thread::firstWhere('first_post_id', $thread)) {
            $package['thread'] = $threadModel->toArray();
        }

        return ['prefetch' => $package];
    }
}
