<?php

namespace Tests\Feature;

use App\Models\Board;
use App\Models\Image;
use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class ImageTest extends TestCase
{

    public function test_create_thread_with_image()
    {
        $filename = \Str::uuid().'.jpg';
        $board = Board::with('threads')->first();
        $response = $this->post(route('create:thread'), [
            'user_name' => 'Anonymous',
            'board_id' => $board->id, 'title' => 'Black square thread',
            'images' => [UploadedFile::fake()->image($filename)]
        ]);

        $response->assertOk();
        $img = Image::firstWhere('name', $filename);
        $this->assertNotNull($img);
        Storage::disk('public')->assertExists("upload/{$img->id}.jpg");
    }

    public function test_reply_with_image()
    {
        $uuid = \Str::uuid();
        $response = $this->post(route('create:post'), [
            'thread_id' => Thread::first()->id,
            'content' => 'Look at this black square',
            'images' => [
                UploadedFile::fake()->image("$uuid.jpg"),
            ],
        ]);

        $response->assertOk();

        /** @var Image $img */
        $img = Image::firstWhere('name', $uuid.'.jpg');
        $this->assertNotNull($img);
        Storage::disk('public')->assertExists("upload/{$img->id}.jpg");
    }

    public function test_reply_with_multiple_images()
    {
        $filenames = [Str::uuid().'.jpg', Str::uuid().'.jpg'];

        $files = collect($filenames)->map(fn($filename) => UploadedFile::fake()->image($filename));
        $response = $this->post(route('create:post'), [
            'thread_id' => Thread::first()->id,
            'content' => 'Imagine posting only one black square',
            'images' => $files->values()->toArray(),
        ]);

        $response->assertOk();

        /** @var Image $img */
        $img = Image::firstWhere('name', $filenames[0]);
        $this->assertNotNull($img);

        Storage::disk('public')->assertExists("upload/{$img->id}.jpg");

        $img = Image::firstWhere('name', $filenames[1]);
        $this->assertNotNull($img);
    }
}
