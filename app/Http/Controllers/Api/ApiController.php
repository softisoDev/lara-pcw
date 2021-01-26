<?php


namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ApiRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


class ApiController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $request;

    public function __construct(ApiRequest $request)
    {
        $this->setRequest($request);
    }

    public function setRequest($request)
    {
        $this->request = $request;
    }
}
