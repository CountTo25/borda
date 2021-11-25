<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $token_id
 * @property int $thread_id
 * @property int $id
 */
class Subscription extends Model
{
    use HasFactory;
}
