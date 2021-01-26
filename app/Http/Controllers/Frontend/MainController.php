<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * @method boot()
 */
class MainController extends Controller
{

    public $viewFolder;

    public $pageTitle;

    public function __construct()
    {
        $this->viewFolder = 'frontend';
        $this->pageTitle = 'larapcw.com';
        if (method_exists($this, 'boot')) {
            $this->boot();
        }
    }

    /**
     * @param $slug
     * @param $search
     */
    public function checkUrl($slug, $search)
    {
        if (!preg_match("/\b({$search})\b/", $slug))
            return abort(404);
    }


    /**
     * @param $view
     * @param $data
     * @return Factory|View
     */
    public function render($view, $data = array())
    {
        $viewData = [
            'navbar' => Category::navbar(),
        ];

        return view($view)->with(array_merge($viewData, $data));
    }

    /**
     * @param $call
     * @return mixed
     */
    public function catch($call)
    {
        try {
            return app()->call($call);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage() . ' Line: ' . $exception->getLine() . ' in ' . $exception->getFile());
        }
    }


}
