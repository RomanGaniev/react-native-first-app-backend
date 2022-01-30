<?php

namespace App\Http\Requests\Chat\Message;

use App\Http\Requests\Chat\ShowChatRequest;

class StoreMessageRequest extends ShowChatRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'text' => 'required',
        ];
    }
}
