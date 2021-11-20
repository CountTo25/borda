<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ReplyParser
{

    const PREFIXES = ['>', '@', '\/'];

    public function handle(string $message): array
    {
        $find = collect(self::PREFIXES)->map(fn ($prefix) => '/'.$prefix.'(\d*)/m')->toArray();
        $matched = [];
        foreach ($find as $regex) {
            $matches = [];
            preg_match_all($regex, $message, $matches);
            $matched = [...$matched, ...$matches[1]];
        }

        /** @var Collection<Post> $posts */
        $posts = Post::whereIn('id', $matched)->select(['id', 'thread_id'])->get();
        return $posts->toArray();
    }
}
