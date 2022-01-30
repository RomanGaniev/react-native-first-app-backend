<?php

namespace App\Http\Requests\Chat;

use App\Http\Requests\BaseFormRequest;
use App\Models\Chat;
use App\Policies\Abilities;

class DestroyChatRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $chat = Chat::query()->find($this->route('id'));

        return $chat && $this->user()->can(Abilities::DESTROY, $chat);
    }
}
