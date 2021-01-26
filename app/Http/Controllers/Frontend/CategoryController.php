<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Schema;
use App\Models\Category;
use App\Repository\ProductRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

class CategoryController extends MainController
{

    public $subViewFolder;

    public function boot()
    {
        $this->subViewFolder = 'category';
    }

    /**
     * @param ProductRepository $repository
     * @param $slug
     * @param Category $category
     * @return Factory|RedirectResponse|Redirector|View
     */
    public function index(ProductRepository $repository, $slug, Category $category)
    {
        $this->checkUrl($slug, $category->slug);

        $products = $repository->findByCategory($category);

        $viewData = [
            'pageTitle'          => makeCategoryTitle($category, $products->currentPage()),
            'description'        => is_null($category->description) ? makeCategoryMetaDesc($category, $products->currentPage()) : $category->description,
            'category'           => $category,
            'products'           => $products,
//            'tags'               => tagExtractor($products),
            'tags'               => $category->getTags(),
            'categoryBreadcrumb' => Schema::categoryBreadcrumb($category),
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
