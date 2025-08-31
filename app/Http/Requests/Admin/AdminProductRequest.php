<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'        => ['required', 'string', 'min:2', 'max:500'],
            'description' => ['required', 'string', 'min:2', 'max:10000'],
            'price'       => ['required', 'numeric', 'min:0'], // ✅ Added price validation
        ];
    }
}
