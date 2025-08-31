<?php

namespace App\Http\Requests\Api;

use App\Helpers\CommonHelper;
use App\Rules\Digits;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $id = request()->id;

        $rules =  [

            'id'                 => ['required', 'integer', 'exists:users,id'],
            'first_name'         => ['required', 'string', 'min:2', 'max:500'],
            'last_name'          => ['required', 'string', 'min:2', 'max:500'],
            'gender'             => ['required', 'string', 'min:2', 'max:500'],
            'dob'                => ['required', 'date_format:Y-m-d'],
            'country_id'         => ['required', 'integer', 'exists:countries,id'],
            'timezone_id'        => ['required', 'integer', 'exists:timezones,id'],
            'email'              => ['required', 'unique:users,email,' . $id, 'email', 'min:3', 'max:500'],
            'phone'              => ['required', 'unique:users,phone,' . $id, 'numeric'],
            'phone_country_code' => ['required_with:phone', 'string', 'min:2', 'max:10'],
            'fcm_token'          => ['required'],

        ];

        return $rules;
    }

    /**
     * Error messages for defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }

    /**
     * get validated input
     *
     * @return array
     */
    public function getValidatedData()
    {
        $formData = $this->only([
            'id',
            'first_name',
            'last_name',
            'gender',
            'dob',
            'country_id',
            'timezone_id',
            'email',
            'phone',
            'phone_country_code',
            'fcm_token',
        ]);

        return $formData;
    }


    public function all($keys = null)
    {
        $data = parent::all();
        if (isset($data['phone'])) {
            $data['phone'] = CommonHelper::updatePhone($data['phone']);
        }
        return $data;
    }
}
