<?php

namespace App\Http\Requests\CPanel;

use Illuminate\Foundation\Http\FormRequest;

class CategoryStoreRequest extends FormRequest
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
            'parent_category' => 'required',
            'name'            => 'required|unique:categories,name',
            'slug'            => 'required|unique:categories,slug',
            'image'           => 'mimes:jpeg,jpg,png',
        ];
    }
}
