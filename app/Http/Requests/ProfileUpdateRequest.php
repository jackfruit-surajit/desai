<?php

namespace App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class ProfileUpdateRequest extends FormRequest {

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
        return [
            'name' => 'required|regex:/^[a-zA-Z\s]+$/',
            // 'email' => 'required|email|max:255',
            // 'phone' => 'required|numeric',
        ];
    }

    public function withValidator($validator) {
        $validator->after(function ($validator) {
            

            $user = User::findOrFail(Auth::guard('frontend')->user()->id);
            // dd($this->phone);
            if ($user->email !== $this->email) {
                $checkUser = User::where('email', $this->email)->first();
                if (!empty($checkUser)) {
                    $validator->errors()->add('email', 'This email address already taken.');
                }
            }

            if ($user->phone !== $this->phone) {
                $checkUser = User::where('phone', $this->phone)->first();
                if (!empty($checkUser)) {
                    $validator->errors()->add('phone', 'This phone number already taken.');
                }
            }
            
        });
    }

}
