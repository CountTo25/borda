<?php

namespace Tests\Feature;

use App\Events\NewReply;
use App\Models\Post;
use App\Models\Thread;
use App\Models\Token;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    public function test_creates_tokens()
    {
        $this->disableCookieEncryption();
        $tokenCount = Token::all()->count();
        $response = $this->get(route('view:app'));
        $response->assertOk();
        $response->assertCookie('LARABA-TOKEN');
        /** @var Token $token */
        $token = Token::orderByDesc('id')->first();
        $this->assertTrue($tokenCount < Token::all()->count());
    }

    public function test_adds_tokens_to_posts()
    {
        $this->disableCookieEncryption();
        /** @var Token $token */
        $token = Token::orderByDesc('id')->first();
        $tokenValue = $token->token;
        $content = 'I love doing stuff with cookies';
        $response = $this->withCookie('LARABA-TOKEN', $tokenValue)
            ->post(route('create:post'), [
                'thread_id' => Thread::first()->id,
                'content' => $content,
                'user_name' => 'orteil?',
            ]);
        $response->assertOk();
        /** @var Post $post */
        $post = Post::firstWhere('id', $response->json()['post_id']);
        $this->assertTrue($post->token === $tokenValue);
    }

    public function test_gets_subscriptions() {
        $this->disableCookieEncryption();
        /** @var Token $token */
        $token = Token::orderByDesc('id')->first();
        $tokenValue = $token->token;

        $res = $this->withCookie('LARABA-TOKEN', $tokenValue)->get(route('view:app'));
        $res->assertOk();
        $data = $res->getOriginalContent()->getData();

        $this->assertArrayHasKey('prefetch', $data);
        $this->assertArrayHasKey('subscriptions', $data['prefetch']);
    }

    public function test_dispatches_event_at_post() {
        $content = 'Hey, does this dispatch anything?';
        Event::fake([NewReply::class]);
        $response = $this->post(route('create:post'), [
                'thread_id' => Thread::first()->id,
                'content' => $content,
            ]);
        $response->assertOk();

        Event::assertDispatched(NewReply::class);
    }

    public function test_manual_subscription() {
        /** @var Token $token */
        $token = Token::first();
        $subs = $token->subscriptions->count();
        /** @var Thread $thread */
        $thread = Thread::first();
        $response = $this->post(route('token:subscribe'), [
            'token' => $token->token,
            'thread_id' => $thread->id,
        ]);
        $response->assertOk();
        $token = $token->fresh('subscriptions');
        $this->assertTrue($token->subscriptions->count() === ($subs + 1));
    }

    public function test_manual_unsubscription() {
        /** @var Token $token */
        $token = Token::first();
        $subs = $token->subscriptions->count();
        /** @var Thread $thread */
        $thread = Thread::first();
        $response = $this->post(route('token:unsubscribe'), [
            'token' => $token->token,
            'thread_id' => $thread->id,
        ]);
        $response->assertOk();
        $token = $token->fresh('subscriptions');
        $this->assertTrue($token->subscriptions->count() === ($subs - 1));
    }
}
