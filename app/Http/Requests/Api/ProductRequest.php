<?php

namespace App\Http\Requests\Api;


class ProductRequest extends ApiRequest
{
    public function rulesIndex()
    {
        return [
            'asin' => 'required',
            'num_record' => 'numeric',
            'category' => 'numeric',
        ];
    }
}
