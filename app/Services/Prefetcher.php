<?php

namespace App\Services;

use App\Models\Board;
use App\Models\Thread;
use App\Models\Token;
use Illuminate\Http\Request;

class Prefetcher
{
    public function get(): array
    {
        /** @var Request $request */
        $request = request();

        $board = $request->route('board');
        $thread = $request->route('thread');

        $package = [];
        $package['boards'] = Board::select('short_name')->pluck('short_name')->toArray();

        if (
            $board !== null
            && $boardModel = Board::with([
                'threads.latestPosts.images', 'threads.firstPost.images',
                'threads.firstPost.mentions', 'threads.latestPosts.mentions',
            ])->firstWhere('short_name', $board)
        ) {
            $package['board'] = $boardModel->toArray();
        }

        if ($thread !== null
            && $threadModel = Thread::with(['posts.images', 'posts.mentions', 'firstPost.images', 'firstPost.mentions'])
                ->firstWhere('first_post_id', $thread)
        ) {
            $package['thread'] = $threadModel->toArray();
        }

        if (($board !== null && $boardModel === null) || ($thread !== null && $thread === null)) {
            $package['should404'] = true;
        }

        if ($request->has('token')) {
            $package['token'] = $request->get('token');
        }

        if ($request->hasCookie('LARABA-TOKEN')) {
            /** @var Token $token */
            $token = Token::with('subscriptions')
                ->firstWhere('token', $request->cookie('LARABA-TOKEN'));
            if ($token !== null) {
                $package['subscriptions'] = $token->subscriptions;
            }
        }

        return ['prefetch' => $package];
    }
}
