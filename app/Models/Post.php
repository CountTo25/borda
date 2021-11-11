<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;

/**
 * @property string $content
 * @property string $ip
 * @property int $id
 * @property string $user_name
 *
 * @property-read  Thread $thread
 */
class Post extends Model
{
    use HasFactory, SoftDeletes, HasEagerLimit;

    protected $table = 'posts';
    protected $fillable = ['content', 'ip', 'thread_id', 'user_name'];

    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class, 'thread_id');
    }

    protected static function boot() {
        static::creating(function(Post $model) {
            $model->ip = request()->ip() ?? 'undefined';
        });
        parent::boot();
    }
}
