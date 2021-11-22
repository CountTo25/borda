<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $token
 */

class Token extends Model
{
    use HasFactory;

    protected $fillable = ['token'];
}
