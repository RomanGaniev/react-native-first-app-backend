<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class BaseFormRequest extends FormRequest
{
    /**
     * Get form data.
     *
     * @return array
     */
    public function getFormData(): array
    {
        $data = $this->all();

        $data = Arr::except($data, [
            '_method',
        ]);

        return $data;
    }
}
