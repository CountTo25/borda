<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;

class PostReplies extends Model
{
    use HasFactory, HasEagerLimit;

    public $timestamps = false;
    public $incrementing = false;

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function mention(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'mentioned_by_id');
    }
}
