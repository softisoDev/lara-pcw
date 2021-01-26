<?php

namespace App\Http\Requests\Api;

use App\Exceptions\ApiRequestException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class ApiRequest extends FormRequest
{
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
        $methodName = 'rules' . Str::studly($this->getActionMethod());

        return method_exists($this, $methodName) ? $this->{$methodName}() : [];
    }

    public function getActionMethod()
    {
        return $this->route() ? $this->route()->getActionMethod() : '';
    }

    public function failedValidation(Validator $validator)
    {
        throw new ApiRequestException($validator->errors()->all(), 400);
    }
}
