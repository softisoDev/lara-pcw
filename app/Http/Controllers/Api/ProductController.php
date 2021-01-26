<?php


namespace App\Http\Controllers\Api;

use App\Exceptions\ApiGeneralException;
use App\Http\Requests\Api\ProductRequest;
use App\Models\Searches;


class ProductController extends ApiController
{

    public function __construct(ProductRequest $request)
    {
        parent::__construct($request);
    }

    public function index()
    {
        $asin = $this->request->get('asin');

        $this->checkAsin($asin);

        $save = Searches::create(['asin' => $asin]);

        if ( !$save ) {
            return api()->fail('Something went wrong')->toJson();
        }

        return api()->success()->toJson();

    }

    private function checkAsin($asin)
    {
        $search = Searches::where('asin', $asin)->first();

        if ( !is_null($search) ) {
            throw new ApiGeneralException("This ASIN is already exist in table ", 400);
        }
    }
}
