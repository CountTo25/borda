<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\NewPostRequest;
use App\Http\Requests\NewThreadRequest;
use App\Models\Board;
use App\Models\Image;
use App\Models\Post;
use App\Models\PostReply;
use App\Models\Thread;
use App\Models\Token;
use App\Services\ReplyParser;
use App\Services\TSModelServer;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ThreadController extends Controller
{
    //
    public function createPost(NewPostRequest $request, ReplyParser $replyParser): JsonResponse
    {

        $data = $request->validated();
        /** @var Thread $thread */
        $thread = Thread::with('board')->firstWhere('id', $data['thread_id']);

        if (!array_key_exists('user_name', $data) || $data['user_name'] === null) {
            $data['user_name'] = $thread->board->default_username;
        }

        $postable = (array_key_exists('content', $data) || $this->hasImages($data));

        if (!$postable) {
            return response()->json([
                'error' => 'Unable to create post. Post needs either a picture or an image'
            ], 403);
        }

        if ($thread->post_count >= $thread->board->bump_limit) {
            $thread->timestamps = false;
        }

        $post = null;
        $replies = $replyParser->handle($data['content']);
        DB::transaction(function() use ($data, $thread, &$post, $replies) {
            /** @var Post $post */
            $post = Post::create($data);
            if ($this->hasImages($data)) {
                collect($data['images'])
                    ->each(fn (UploadedFile $image) => $post->images()->save(Image::fromUpload($image, $post->id)));
            }

            $thread->update(['post_count' => $thread->post_count + 1]);

            $forFilling = collect($replies)
                ->map(function ($entry) use ($post) {
                    return [
                        'post_id' => $entry['id'],
                        'mentioned_at_id' => $post->id,
                        'same_thread' => $entry['thread_id'] == $post->thread_id,
                        ];
                });

            PostReply::insert($forFilling->toArray());

            /** @var Token $token */
            $token = Token::firstWhere('token', request()->cookie('LARABA-TOKEN'));
            if ($token) {
                $token->subscriptions()->attach($thread);
            }
        });
        return response()->json(['success' => 'created', 'post_id' => $post->id]);
    }

    public function createThread(NewThreadRequest $request): JsonResponse {

        $data = $request->validated();

        if (array_key_exists('content', $data) && $data['content'] !== null) {
            $data['content'] = strip_tags($data['content']);
        }

        $post = null;
        DB::transaction(function() use ($data, &$post) {
            /** @var Thread $thread */
            $thread = Thread::create(['title' => $data['title'], 'board_id' => $data['board_id']]);
            /** @var Post $post */
            $post = $thread->firstPost()->create([
                'content' => $data['content'] ?? null,
                'user_name' => $data['user_name'] ?? $thread->board->default_username ?? 'Anonymous',
            ]);
            if ($this->hasImages($data)) {
                collect($data['images'])
                    ->each(fn (UploadedFile $image) => $post->images()->save(Image::fromUpload($image, $post->id)));
            }
            $thread->update(['first_post_id' => $post->id]);
        });

        return response()->json(['thread_id' => $post->id]);
    }

    public function listThreads(TSModelServer $tsapi) {
        return $tsapi->allowWith([
            'posts.mentions', 'posts.images', 'latestPosts.images',
            'firstPost.images', 'board', 'firstPost.mentions',
            'latestPosts.mentions',
        ])->respond(
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

    private function hasImages(?array $subject): bool {
        return array_key_exists('images', $subject) && $subject['images'] !== null && count($subject['images']) > 0;
    }
}
