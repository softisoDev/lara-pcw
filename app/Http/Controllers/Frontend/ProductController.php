<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Schema;
use App\Models\Category;
use App\Models\Product;
use App\Repository\ProductRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends MainController
{
    public $subViewFolder;

    public function boot()
    {
        $this->subViewFolder = 'product';
    }

    public function index()
    {
        $viewData = [
            'pageTitle' => $this->pageTitle,
            'products'  => Product::with(['variations'])->get(),
        ];

        return $this->render("{$this->viewFolder}.{$this->subViewFolder}.index", $viewData);
    }

    /**
     * @param ProductRepository $repository
     * @param $product
     * @param $slugProduct
     * @return Factory|View
     */
    public function show(ProductRepository $repository, $product)
    {
        $product = $repository->single($product);

        $viewData = [
            'pageTitle'         => $product->title,
            'description'       => makeSingleProductMetaDesc($product),
            'product'           => $product,
            'schema'            => Schema::singleProduct($product),
            'features'          => findInFeatures($product->features),
            'category'          => $product->main_category,
            'media'             => $product->media,
            'productBreadcrumb' => Schema::productBreadcrumb($product->main_category, $product),
        ];

        return $this->render("{$this->viewFolder}.{$this->subViewFolder}.single", $viewData);
    }

    /**
     * @param Request $request
     * @param Product $product
     * @param Category $category
     * @return mixed
     */
    public function similar(Request $request, Product $product, Category $category)
    {
        return view("{$this->viewFolder}.{$this->subViewFolder}.similar")->with([
            'products' => $product->similar($category->id)
        ])->render();
    }

    /**
     * redirect to random product
     * @param ProductRepository $repository
     * @return mixed
     */
    public function random(ProductRepository $repository)
    {
        return redirect('/products/' . $repository->random() . '/?r=a');
    }

}
