<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewThreadRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['string', 'min:3', 'max:100'],
            'board_id' => ['required', 'exists:boards,id'],
            'content' => ['string', 'min:3', 'max:2048', 'nullable'],
            'user_name' => ['string', 'min:1', 'max:100', 'nullable'],
            'images' => ['nullable', 'array', 'max:3'],
            'images.*' => ['mimes:jpeg,jpg,png', 'max:4096'],
        ];
    }
}
