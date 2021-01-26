<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Tag;
use App\Repository\ProductRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;


class TagController extends MainController
{

    public $subViewFolder;

    public function boot()
    {
        $this->subViewFolder = 'tag';
    }

    /**
     * @param ProductRepository $repository
     * @param Tag $tag
     * @return Factory|RedirectResponse|Redirector|View
     */
    public function index(ProductRepository $repository, $tag)
    {
        $tag = Tag::single($tag);

        $products = $repository->findByTag($tag);

        $viewData = [
            'pageTitle'   => $tag->title,
            'description' => $tag->description,
            'products'    => $products,
            'tag'         => $tag,
        ];

        if ($viewData['products']->currentPage() > $viewData['products']->lastPage() && $viewData['products']->currentPage() != 1) {
            return redirect(url()->current());
        }

        if ($viewData['products']->currentPage() == 1) {
            $viewData['reviews'] = array_filter(array_column($products->items(), 'singleRandomReview'));
        }

        return $this->render("{$this->viewFolder}.{$this->subViewFolder}.index", $viewData);
    }


}
