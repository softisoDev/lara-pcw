<?php

namespace App\Http\Requests\CPanel;

use Illuminate\Foundation\Http\FormRequest;

class BrandStoreRequest extends FormRequest
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
            'parent_brand' => 'required',
            'name'         => 'required|unique:brands,name',
            'slug'         => 'required|unique:brands,slug',
            'image'        => 'mimes:jpeg,jpg,png',
        ];
    }
}
