<?php

namespace App\Http\Requests\Chat;

use App\Http\Requests\BaseFormRequest;

class StoreChatRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required|string',
            'name' => 'required_without:interlocutor_id|max:25',
            'avatar' => 'image',
            'interlocutor_id' => 'integer',
            'participants' => 'array'
        ];
    }

    public function getFormData(): array
    {
        $data = parent::getFormData();
        $data['avatar'] = $this->file('avatar');

        return $data;
    }
}
