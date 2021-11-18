<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;

/**
 * @property string $title
 * @property string $content
 * @property int $board_id
 * @property int $post_count
 * @property int $first_post_id
 *
 * @property-read Board $board
 * @property-read Collection<Post> $latestPosts
 */
class Thread extends Model
{
    use HasFactory, SoftDeletes, HasEagerLimit;

    protected $fillable = ['board_id', 'content', 'title', 'first_post_id', 'post_count'];

    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class, 'board_id');
    }

    public function latestPosts(): HasMany
    {
        return $this->hasMany(Post::class, 'thread_id')
            ->latest('updated_at')
            ->take(2);
    }

    public function firstPost(): HasOne
    {
        return $this->hasOne(Post::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'thread_id');
    }
}
