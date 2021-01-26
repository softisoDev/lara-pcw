<?php

namespace App\Http\Controllers\Api;


use App\Http\Requests\Api\CategoryRequest;
use App\Models\Category;

class CategoryController extends ApiController
{
    private $model;

    public function __construct(CategoryRequest $request)
    {
        parent::__construct($request);
        $this->model = new Category();
    }

    public function index()
    {
        return api($this->model::onlyParents(['id', 'name'])->toArray())->success()->toJson();
    }
}
