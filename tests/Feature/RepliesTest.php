<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\PostReply;
use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RepliesTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_single_reply()
    {
        $response = $this->post(route('create:post'), [
            'thread_id' => Thread::first()->id,
            'content' => 'Pineapple pizza is the best',
            'user_name' => 'countto25',
        ]);

        $post = Post::firstWhere('id', $response->json('post_id'));

        $response = $this->post(route('create:post'), [
            'thread_id' => Thread::first()->id,
            'content' => "Holy smokes, >{$post->id} is insane!",
        ]);

        $response->assertStatus(200);

        $reply = PostReply::where('post_id', $post->id)
            ->where('mentioned_at_id', $response->json('post_id'))
            ->first();

        $this->assertNotNull($reply);
    }

    public function test_multiple_replies() {
        $response = $this->post(route('create:post'), [
            'thread_id' => Thread::first()->id,
            'content' => 'I think SMTV is Persona 5 but without the heart',
        ]);

        $post = Post::firstWhere('id', $response->json('post_id'));

        $response = $this->post(route('create:post'), [
            'thread_id' => Thread::first()->id,
            'content' => "Mods, why >{$post->id} isnt banned yet?",
        ]);

        $reply = Post::firstWhere('id', $response->json('post_id'));

        $response = $this->post(route('create:post'), [
            'thread_id' => Thread::first()->id,
            'content' => "Seconded >{$reply->id}, ban >{$post->id}",
        ]);

        $responses = PostReply::whereIn('mentioned_at_id', [$response->json('post_id'), $reply->id])
            ->where('post_id', $post->id)->get();

        $this->assertTrue($responses->count() === 2);
    }
}
