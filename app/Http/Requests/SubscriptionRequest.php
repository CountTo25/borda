<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequest extends FormRequest
{

    public function rules()
    {
        return [
            'token' => ['required', 'string', 'exists:tokens'],
            'thread_id' => ['required', 'exists:threads,id']
        ];
    }
}
