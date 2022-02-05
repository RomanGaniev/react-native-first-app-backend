<?php

namespace App\Http\Requests\Post;

use App\Http\Requests\BaseFormRequest;

class StorePostRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'text' => 'required_without:image|max:500',
            'image' => 'required_without:text|image'
        ];
    }
}
