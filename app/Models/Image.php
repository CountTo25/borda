<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @property int $id
 * @property string $name
 * @property string $extension
 *
 * @property-read Post $post
 * @psalm-seal-properties
 */
class Image extends Model
{
    use HasFactory, HasEagerLimit;

    protected $fillable = ['name', 'extension'];
    protected $appends = ['url'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public static function fromUpload(UploadedFile $source, int $post_id): self
    {
        print_r($source);
        $image = new self();
        $image->fill([
            'name' => $source->getClientOriginalName(),
            'extension' => $source->getClientOriginalExtension(),
        ])->save();

        Storage::disk('public')->put('upload/'.$image->id.'.'.$image->extension, $source->getContent());

        return $image;
    }

    public function getUrlAttribute() {
        return Storage::disk('public')->url('upload/'.$this->id.'.'.$this->extension);
    }
}
