<?php

namespace App\Models;

use App\Services\Dactyloscopy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;

/**
 * @property string $content
 * @property string $ip
 * @property int $id
 * @property string $user_name
 * @property int $thread_id
 * @property int $fingerprint
 * @property string $token
 *
 * @property-read  Thread $thread
 * @property-read Collection<Image> $images
 * @property-read bool $own
 * @psalm-seal-properties
 */
class Post extends Model
{
    use HasFactory, SoftDeletes, HasEagerLimit;

    protected $table = 'posts';
    protected $fillable = ['content', 'ip', 'thread_id', 'user_name'];
    protected $appends = ['own'];

    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class, 'thread_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class, 'post_id');
    }

    protected static function boot() {
        static::creating(function(Post $model) {
            $model->ip = request()->ip() ?? 'undefined';
            $model->fingerprint = app(Dactyloscopy::class)->make();
            $model->token = request()->cookie('LARABA_TOKEN');
        });
        parent::boot();
    }

    public function mentions(): HasMany
    {
        return $this->hasMany(PostReply::class, 'post_id')->where('same_thread', true);
    }

    public function getOwnAttribute() {
        return app(Dactyloscopy::class)->check($this->fingerprint);
    }
}
