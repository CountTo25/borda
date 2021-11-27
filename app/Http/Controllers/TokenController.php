<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplyTokenRequest;
use App\Http\Requests\SubscriptionRequest;
use App\Models\Thread;
use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TokenController extends Controller
{
    //

    public function apply(ApplyTokenRequest $request) {
        return Token::with('subscriptions')->firstWhere('token', $request->get('token'));
    }

    public function subscribe(SubscriptionRequest $request) {
        /** @var Token $token */
        $token = Token::firstWhere('token', $request->get('token'));
        $token->subscriptions()->attach(Thread::firstWhere('id', $request->get('thread_id')));
        return response()->json(['success']);
    }

    public function unsubscribe(SubscriptionRequest $request) {
        /** @var Token $token */
        $token = Token::firstWhere('token', $request->get('token'));
        $token->subscriptions()->detach(Thread::firstWhere('id', $request->get('thread_id')));
        return response()->json(['success']);
    }
}
