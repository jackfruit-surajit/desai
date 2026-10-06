<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class ForgotRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $rules = [
            'forgot_password_by' => 'required',
            'phone' => [
                'required_if:forgot_password_by,by_phone',
                function ($attribute, $value, $fail) {
                    if ($this->forgot_password_by === 'by_phone') {
                        if (!is_numeric($value) || strlen($value) != 10) {
                            $fail('The phone number must be numeric and 10 digits long.');
                        }
                    }
                },
            ],
        ];

        // Conditionally apply the email validation when forgot_password_by is by_email
        if ($this->forgot_password_by === 'by_email') {
            $rules['email'] = 'required|email';
        }

        return $rules;
    }
    public function messages() {
        return [
            'forgot_password_by.required' => 'The forgot password method is required.',
            'email.required_if' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'phone.required_if' => 'The phone number is required.',
            'phone.numeric' => 'The phone number must be numeric.',
            'phone.digits' => 'The phone number must be exactly 10 digits long.',
        ];
    }

    public function withValidator($validator) {
        $validator->after(function ($validator) {
            if(isset($this->email)){
            $model = User::where('type_id', '=', '2')->where('email', '=', $this->email)->where('status', '=', '1')->first();
            if (empty($model))
                $validator->errors()->add('email', 'We could not find the Email that you are looking for.');
            }
            if(isset($this->phone)){
            $model = User::where('type_id', '=', '2')->where('phone', '=', $this->phone)->where('status', '=', '1')->first();
            if (empty($model))
                $validator->errors()->add('phone', 'We could not find the Phone No. that you are looking for.');
            }
        });
    }

}
