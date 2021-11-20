<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewPostRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'content' => ['string', 'min:1', 'max:2048', 'nullable'],
            'thread_id' => ['required', 'integer', 'exists:threads,id'],
            'user_name' => ['string', 'min:1', 'max:100', 'nullable'],
            'images' => ['nullable', 'array'],
            'images.*' => ['mimes:jpeg,jpg,png', 'max:4096'],
        ];
    }
}
