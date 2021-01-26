<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Requests\Frontend\SearchRequest;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SearchController extends MainController
{
    public $subViewFolder;
    public $query;

    public function boot()
    {
        $this->subViewFolder = "search";
    }


    function index(SearchRequest $request)
    {
        $this->query = $request->query('keyword');

        $this->saveQuery();

        return redirect(addSlash2Url(route('front.search.do', ['query' => $this->query])));
    }

    public function doSearch(Request $request, $query)
    {
        $this->query = addslashes(str_replace('-', ' ', $query));

        $products = Product::search('title:' . $this->query . '*')
            ->query(function (Builder $builder) {
                $builder->with([
                    'cheapest',
                    'reviewDetail',
                    'gridMainImageUrl',
                ]);
                $builder->whereHas('variations');
                $builder->orderByDesc('created_at');

            })->customPagination(20)->onEachSide(1);
        
        $result = $products->setCollection($products->cgrid());

        $viewData = array(
            'pageTitle' => 'Search result for ' . $this->query,
            'products'  => $result,
            'keyword'   => $this->query,
        );

        return $this->render("{$this->viewFolder}.{$this->subViewFolder}.result", $viewData);
    }

    protected function saveQuery()
    {
        $find = Tag::where('slug', trim($this->query));

        if (!$find->first()) {
            Tag::create(['slug' => trim($this->query), 'status' => 0, 'search_count' => 1]);
        } else {
            $find->increment('search_count', 1);
        }
    }

}
