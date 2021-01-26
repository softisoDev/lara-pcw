<?php

namespace App\Http\Requests\CPanel;

use App\Rules\checkCurrentPassword;
use Illuminate\Foundation\Http\FormRequest;

class UserPasswordUpdateRequest extends FormRequest
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
            'current_password'     => ['required', new checkCurrentPassword],
            'new_password'         => 'required|min:8|different:current_password',
            'confirm_new_password' => 'required|same:new_password',
        ];
    }
}
