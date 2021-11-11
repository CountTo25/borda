<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property boolean $is_hidden
 * @property boolean $is_readonly
 * @property boolean $is_text_only
 *
 * @property string $name
 * @property string $short_name
 * @property string $default_username
 *
 * @property int $bump_limit
 * @property int $max_threads
 *
 * @property-read Collection<Thread> $threads
 */
class Board extends Model
{
    use HasFactory;

    protected $table = 'boards';
    protected $casts = [
        'is_hidden' => 'boolean',
        'is_readonly' => 'boolean',
        'is_text_only' => 'boolean',
    ];

    protected $fillable = [
        'short_name',
        'max_threads',
        'bump_limit',
        'max_threads',
        'name',
        'default_username',
        'is_text_only',
        'is_hidden',
        'is_readonly',
    ];

    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class, 'board_id')->orderByDesc('updated_at');
    }

}
