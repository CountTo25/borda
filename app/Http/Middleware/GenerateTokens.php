<?php

namespace App\Http\Middleware;

use App\Models\Token;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class GenerateTokens
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var Response $response */
        if ((!$request->hasCookie('LARABA-TOKEN'))) {
            do {
                $token = Str::of(Str::random(10))->upper();
            } while (Token::firstWhere(compact('token')) !== null);
            Token::create(compact('token'));
            $token = (string) $token;
            $request->merge(compact('token'));
        } else {
            $token = $request->cookie('LARABA-TOKEN');
            if ((Token::firstWhere('token', $token)) === null) {
                Token::create(compact('token'));
                $request->merge(compact('token'));
            }
        }

        return $next($request);
    }
}
