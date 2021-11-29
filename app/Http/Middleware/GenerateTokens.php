<?php

namespace App\Http\Middleware;

use App\Models\Token;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class GenerateTokens
{
    public function handle(Request $request, Closure $next)
    {
        $created = null;
        if ((!$request->hasCookie('LARABA-TOKEN'))) {
            do {
                $token = Str::of(Str::random(10))->upper();
            } while (Token::firstWhere(compact('token')) !== null);
            Token::create(compact('token'));
            $token = (string) $token;
            $created = $token;
            $request->merge(compact('token'));
        } else {
            $token = $request->cookie('LARABA-TOKEN');
            if ((Token::firstWhere('token', $token)) === null) {
                Token::create(compact('token'));
                $request->merge(compact('token'));
            }
        }

        /** @var Response $response */
        $response = $next($request);

        $response->withCookie(cookie()->forever('LARABA-TOKEN', $created));

        return $response;
    }
}
