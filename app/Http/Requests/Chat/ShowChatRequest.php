<?php

namespace App\Http\Requests\Chat;

use App\Http\Requests\BaseFormRequest;
use App\Models\Chat;
use App\Policies\Abilities;

class ShowChatRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $chat = Chat::query()->find($this->route('chatId'));

        return $chat && $this->user()->can(Abilities::VIEW, $chat);
    }
}
