<?php


namespace App\Repository;


use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Carbon;

class HomePageRepository
{
    public function get($limit = 4)
    {
        $cacheKey = Product::CACHE_NAME['homepage'];

        return Product::cacheStore($cacheKey, Carbon::now()->addMonths(), function () use ($limit) {
            $categories = Category::select([
                'id',
                '_lft',
                '_rgt',
                'parent_id',
                'name',
                'slug',
            ])->get();

            $result = collect();

            foreach ($categories->whereNull('parent_id') as $category) {

                $products = Product::with([
                    'cheapestWithoutSource',
                    'gridMainImageUrl',
                ])
                    ->select(['id', 'sp_hash', 'title'])
                    ->whereHas('variations')
                    ->whereHas('category',
                        function ($q) use ($category, $categories) {
                            $q->whereIn('category_id', $categories->toFlatTree($category->id)->pluck('id')->add($category->id));
                        }
                    )
//                    ->orderByDesc('updated_at')
                    ->inRandomOrder()
                    ->limit($limit)
                    ->get()->grid();

                if ( $products->isNotEmpty() ) {
                    $result->add(array(
                        'category' => $category->toArray(),
                        'products' => $products->toArray(),
                    ));
                }
            }

            return $result->toArray();
        });
    }
}
