<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;

/**
 * @property int $thread_id
 * @property bool $same_thread
 */

class PostReply extends Model
{
    use HasFactory, HasEagerLimit;

    public $timestamps = false;
    public $incrementing = false;
    protected $table = 'post_replies';

    protected $fillable = [
        'post_id', 'mentioned_by_id', 'same_thread'
    ];

    protected $casts = [
        'same_thread' => 'boolean'
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function mention(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'mentioned_by_id');
    }
}
