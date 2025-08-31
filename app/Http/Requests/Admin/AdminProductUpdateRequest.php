<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminProductUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'        => ['sometimes', 'string', 'min:2', 'max:500'],
            'description' => ['sometimes', 'string', 'min:2', 'max:10000'],
            'price'       => ['sometimes', 'numeric', 'min:10', 'max:10000'],
        ];
    }
}
