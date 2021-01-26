<?php


namespace App\Repository;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ProductRepository
{
    protected $request;

    protected $ebaySearchUrl = 'https://www.ebay.com/sch/i.html';
    protected $walmartSearchUrl = 'https://www.walmart.com/search/';

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function findByCategory($category)
    {
        $page = $this->request->page;
        $cacheKey = sprintf(Category::CACHE_NAME['pages'], $category->id, is_null($page) ? 1 : $page);

        return Category::cacheStore($cacheKey, Carbon::now()->addMonths(), function () use ($page, $category) {

            /**
             * @var $products Collection
             * @var $category Category
             */

            $categoryIds = $category->getDescendantsIdWithSelf();

            $with = [
                'cheapest',
                'reviewDetail',
                'gridMainImageUrl',
                /*'tag' => function ($query) {
                    $query->select(['id', 'slug', 'product_id']);
                },*/
            ];

            if ( is_null($page) ) {
                array_push($with, 'singleRandomReview');
            }

            $products = Product::with($with)
                ->select(['id', 'sp_hash', 'title', 'description'])
                ->whereHas('variations')
                ->whereHas('category', function ($q) use ($categoryIds) {
                    $q->select(['id', 'category_id', 'product_id'])->whereIn('category_id', $categoryIds);
                })
                ->orderByDesc('created_at')
                ->paginate(20)
                ->onEachSide(1);

            $products->setPath(addSlash2Url($products->path()));

            return $products->setCollection($products->cgrid());

        });
    }

    public function findByTag($tag)
    {
        $page = $this->request->page;
        $cacheKey = sprintf(Tag::CACHE_NAME['pages'], $tag->id, is_null($page) ? 1 : $page);

        return Tag::cacheStore($cacheKey, Carbon::now()->addMonths(3), function () use ($tag, $page) {
            $with = [
                'cheapest',
                'reviewDetail',
                'gridMainImageUrl',
            ];

            if ( is_null($page) ) {
                array_push($with, 'singleRandomReview');
            }

            $products = Product::search('title:' . $tag->resolveOption() . '*')
                ->query(function (Builder $builder) use ($with) {
                    $builder->with($with);
                    $builder->whereHas('variations');
                    $builder->orderByDesc('created_at');
                })
                ->customPagination(20)
                ->onEachSide(1);

            return $products->setCollection($products->cgrid());
        });
    }

    public function single($hash)
    {
        $cacheKey = sprintf(Product::CACHE_NAME['single'], $hash);

        $product = Product::cacheStore($cacheKey, Carbon::now()->addMonths(), function () use ($hash) {

            return Product::with([
                'media',
                'variations',
                'mainCategory',
                'reviewDetail',
                'brand'             => function ($query) {
                    $query->select(['id', 'name']);
                },
                'tag'               => function ($query) {
                    $query->select(['id', 'slug', 'product_id']);
                },
                'variations.source' => function ($query) {
                    $query->select(['id', 'name']);
                },
                'review'            => function ($query) {
                    $query->select(['id', 'title', 'rating', 'product_id', 'text', 'published_at', 'user_name', 'created_at'])->limit(10);
                },
            ])
                ->where('sp_hash', $hash)
                ->get()
                ->first();

        });

        if ( is_null($product) ) {
            abort(404);
        }

        $this->refineVariation($product);

        return $product;
    }

    private function refineVariation($product)
    {

        addTag($product, 'amazon.com', 'tag=larapcw-20');

        if ( count($product->variations) == 1 ) {
            $this->addAlternativeVariation($product, 'ebay');
            $this->addAlternativeVariation($product, 'walmart');
        }
    }

    private function addAlternativeVariation($product, string $source)
    {
        $sampleVariation = clone $product->variations->first();
        $sampleVariation->source = clone $product->variations->first()->source;

        $url = '';
        $newPrice = $product->variations->first()->current_price;
        $newSource = new \stdClass();
        $newSource->name = '';
        $newSource->id = null;

        switch ($source) {
            case ($source === 'ebay'):
                $query = [
                    '_nkw'      => $product->title,
                    'icep_ff3'  => '1',
                    'pub'       => '5575365',
                    'toolid'    => '10001',
                    'campid'    => '533575802870',
                    '_trkparms' => '59:0',
                    '_trksid'   => 'p2164.m52',
                ];

                $url = $this->ebaySearchUrl . '?' . http_build_query($query);
                $newSource->name = 'ebay.com';
                $newSource->id = 2;
                $newPrice = $this->increaseNumberPercent($newPrice, 4);
                break;
            case 'walmart':
                $query = [
                    'query'       => $product->title,
                    "irgwc"       => "1",
                    "sourceid"    => "imp_1c-y:exaXxyLWo3EFUkE2Fi0NBRSlSw0",
                    "veh"         => "aff",
                    "wmlspartner" => "imp_11657548",
                    "clickid"     => "1c-y:exaXxyLWg3EFUkE2Fi0NBRSlSw0",
                    "sharedid"    => "",
                ];

                $url = $this->walmartSearchUrl . '?' . http_build_query($query);
                $newSource->name = 'walmart.com';
                $newSource->id = 8;
                $newPrice = $this->increaseNumberPercent($newPrice, 8);
                break;
            default:
                break;
        }

        $sampleVariation->source_url = $url;
        $sampleVariation->current_price = $newPrice;
        $sampleVariation->source = $newSource;

        $product->variations[] = $sampleVariation;
    }

    private function increaseNumberPercent($price, $percent)
    {
        return number_format($price + (($price / 100) * $percent), 2);
    }

    public function random(): string
    {
        return  Product::all(['sp_hash'])->random()->sp_hash;
    }
}
