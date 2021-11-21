<?php

namespace Database\Seeders;

use App\Http\Controllers\ThreadController;
use App\Models\Board;
use App\Models\Image;
use App\Models\Post;
use App\Models\Thread;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $board = Board::create([
            'name' => 'Development',
            'default_username' => 'Anonymous',
            'short_name' => 'dev', 'bump_limit' => 500
        ]);

        DB::transaction(function() use ($board) {
            /** @var Thread $thread */
            $thread = Thread::create(['title' => 'Test thread', 'board_id' => $board->id]);
            /** @var Post $post */
            $post = $thread->firstPost()->create([
                'content' => 'Yo, i made some crappy imageboard template, check it out',
                'user_name' => $board->default_username,
            ]);

            $thread->update(['first_post_id' => $post->id]);
        });
    }
}
