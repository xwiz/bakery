<?php

namespace App\Http\Requests\API;

class LoginAPIRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'email' => 'email',
            'phone_number' => 'numeric',
            'password' => 'required'
        ];
    }

    public function authorize()
    {
        return true;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.email' => 'Your email must follow the normal email standard',
            'phone_number.numeric' => 'Phone number must be a number',
            'password.required' => 'Kindly provide a password',
        ];
    }
}
