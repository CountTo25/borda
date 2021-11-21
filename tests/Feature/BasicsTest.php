<?php

namespace Tests\Feature;

use App\Models\Board;
use App\Models\Post;
use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BasicsTest extends TestCase
{
    public function test_list_boards()
    {
        $response = $this->post(route('list:boards'));
        $response->assertStatus(200);
    }

    public function test_create_thread() {
        /** @var Board $board */
        $board = Board::with('threads')->first();
        $threadCount = $board->threads->count();
        $title = 'Non-first thread';
        $content = 'Imagine automatically posting to the first thread';
        $response = $this->post(route('create:thread'), [
            'content' => $content,
            'board_id' => $board->id, 'title' => $title,
        ]);

        $response->assertStatus(200);

        $board = $board->fresh('threads');

        //tests may be run on runtime, so lets use that
        //tbh i think i should rethink this one a bit
        //TODO: teardown
        $this->assertTrue($board->threads->count() === $threadCount + 1);

        /** @var Post $post */
        $post = Post::with('thread')->firstWhere('id', $response->json('thread_id'));

        $this->assertTrue($post->thread->title === $title);
        $this->assertTrue($post->content === $content);
    }

    public function test_create_post() {
        $content = 'Am i first to post in first thread?';
        $response = $this->post(route('create:post'), [
            'thread_id' => Thread::first()->id,
            'content' => $content,
            'user_name' => 'countto25',
        ]);

        $response->assertStatus(200);

        $post = Post::firstWhere('id', $response->json('post_id'));

        $this->assertTrue($post->content === $content);
        $this->assertTrue($post->user_name === 'countto25');
        $this->assertTrue($post->ip !== null);

        $response = $this->post(route('create:post'), [
            'thread_id' => Thread::first()->id,
            'content' => 'Wow, first thread, gj'
        ]);

        $response->assertStatus(200);
        /** @var Post $post */
        $post = Post::with('thread.board')
            ->firstWhere('id', $response->json('post_id'));

        $this->assertTrue($post->user_name === $post->thread->board->default_username);
    }
}
