<?php

namespace Tests\Feature;

use App\Models\Board;
use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PrefetchTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_prefetching_app() {
        $res = $this->get(route('view:app'));
        $data = $res->getOriginalContent()->getData();

        $this->assertArrayHasKey('prefetch', $data);
        $this->assertArrayHasKey('boards', $data['prefetch']);

        $this->assertTrue(in_array(Board::first()->short_name, $data['prefetch']['boards']));
    }

    public function test_prefetching_board() {
        $res = $this->get(route('view:board', ['board' => Board::first()->short_name]));
        $data = $res->getOriginalContent()->getData();

        $this->assertArrayHasKey('prefetch', $data);
        $this->assertArrayHasKey('board', $data['prefetch']);
        $this->assertTrue($data['prefetch']['board']['short_name'] === Board::first()->short_name);
    }

    public function test_prefetching_thread() {
        /** @var Board $board */
        $board = Board::with('threads')->first();
        /** @var Thread $thread */
        $thread = $board->threads->first();
        $res = $this->get(route('view:thread',
            ['board' => $board->short_name, 'thread' => $thread->first_post_id]
        ));

        $data = $res->getOriginalContent()->getData();

        $this->assertArrayHasKey('prefetch', $data);
        $this->assertArrayHasKey('board', $data['prefetch']);
        $this->assertArrayHasKey('thread', $data['prefetch']);

        $this->assertTrue($data['prefetch']['thread']['id'] === $thread->id);
    }

    public function test_prefetching_404() {
        $res = $this->get(route('view:board', ['board' => 'KEKWTEST_DO_NOT_BREAK_PLS']));
        $data = $res->getOriginalContent()->getData();
        $this->assertArrayHasKey('prefetch', $data);
        $this->assertArrayHasKey('should404', $data['prefetch']);
    }
}
