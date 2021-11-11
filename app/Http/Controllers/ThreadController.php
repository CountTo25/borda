<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Post;
use App\Models\Thread;
use App\Services\TSModelServer;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ThreadController extends Controller
{
    //
    public function createPost(Request $request): JsonResponse
    {
        $data = $request->validate([
            'content' => ['string', 'min:1', 'max:2048', 'nullable'],
            'thread_id' => ['required', 'integer', 'exists:threads,id'],
            'user_name' => ['string', 'min:1', 'max:100', 'nullable'],
        ]);

        /** @var Thread $thread */
        $thread = Thread::with('board')->firstWhere('id', $data['thread_id']);

        if (!array_key_exists('user_name', $data) || $data['user_name'] === null) {
            $data['user_name'] = $thread->board->default_username;
        }

        $postable = (array_key_exists('content', $data) || array_key_exists('image', $data));

        if (!$postable) {
            return response()->json(['error' => 'Unable to create post. Post needs either a picture or an image'], 403);
        }

        if ($thread->post_count >= $thread->board->bump_limit) {
            $thread->timestamps = false;
        }

        $post = null;
        DB::transaction(function() use ($data, $thread, &$post) {
            $post = Post::create($data);
            $thread->update(['post_count' => $thread->post_count + 1]);
        });
        return response()->json(['success' => 'created', 'post_id' => $post->id]);
    }

    public function createThread(Request $request): JsonResponse {
        $data = $request->validate([
            'thread.title' => ['string', 'min:3', 'max:100'],
            'thread.board_id' => ['required', 'exists:boards,id'],
            'post.content' => ['string', 'min:3', 'max:2048'],
            'post.user_name' => ['string', 'min:1', 'max:100'],
        ]);

        $post = null;
        DB::transaction(function() use ($data, &$post) {
            /** @var Thread $thread */
            $thread = Thread::create($data['thread']);
            /** @var Post $post */
            $post = $thread->firstPost()->create($data['post']);
            $thread->update(['first_post_id' => $post->id]);
        });

        return response()->json(['thread_id' => $post->id]);
    }

    public function listThreads(TSModelServer $tsapi, Request $request) {
        return $tsapi->allowWith(['posts', 'latestPosts', 'firstPost', 'board'])->respond(
            Thread::class,
        );
    }

    public function listPosts(TSModelServer $tsapi, Request $request) {
        $queryData = $request->validate(['thread_id' => ['required', 'exists:threads,id']]);
        return $tsapi->respond(
            Post::class,
            null,
            fn (Builder $query) => $query->where($queryData)
        );
    }
}
