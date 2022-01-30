<?php

namespace App\Http\Requests\Chat;

use App\Models\Chat;
use App\Policies\Abilities;

class UpdateChatRequest extends StoreChatRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $chat = Chat::query()->find($this->route('id'));

        return $chat && $this->user()->can(Abilities::UPDATE, $chat);
    }
}
