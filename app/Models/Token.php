<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $token
 * @property Collection<Thread> $subscriptions
 */

class Token extends Model
{
    use HasFactory;

    protected $fillable = ['token'];
    public $timestamps = false;

    public function subscriptions(): BelongsToMany
    {
        return $this->belongsToMany(
            Thread::class,
            'token_threads',
            'token_id',
            'thread_id'
        )->withCount('posts');
    }
}
