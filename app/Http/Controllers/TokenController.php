<?php

namespace App\Http\Controllers;

use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TokenController extends Controller
{
    //

    public function make() {
        $token = Str::random(10);
        Token::create(compact('token'));
        return compact('token');
    }
}
