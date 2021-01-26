<?php


namespace App\Libraries;


use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Review;
use App\Models\Source;
use App\Models\Tag;
use Illuminate\Support\Facades\Log;

class ProductImporter
{
    private $tempSaveDir;

    private $permSaveDir;

    private $searchDir;

    private $imageDir;

    private $defaultProductCode;

    protected $savedBrand;

    private $record = null;

    private $category = 1;

    private $tags = null;

    private $spHash;

    public function __construct()
    {
        $this->imageDir = 'products';
        $this->searchDir = env('SEARCH_DIR') . DIRECTORY_SEPARATOR;
        $this->tempSaveDir = env('SEARCH_DIR') . DIRECTORY_SEPARATOR . env('TEMP_SEARCH_DIR') . DIRECTORY_SEPARATOR;
        $this->permSaveDir = env('SEARCH_DIR') . DIRECTORY_SEPARATOR . env('SAVED_SEARCH_DIR') . DIRECTORY_SEPARATOR;

        $this->defaultProductCode = '{"asin":null,"upc":null,"upce":null,"upca":null,"ean":null,"ean8":null,"ean13":null,"vin":null,"gtins":null,"isbn":null}';
    }

    public function record($record): self
    {
        $this->record = $record;
        return $this;
    }

    public function category($category)
    {
        $this->category = $category;
        return $this;
    }

    public function tag($tags)
    {
        $this->tags = $tags;
        return $this;
    }

    public function save()
    {
        $this->saveBrand();

        $tags = $this->saveTags($this->tags);

        if (!is_null($this->checkDuplicate())) {
            return false;
        }

        try {
            //save product
            $product = $this->saveProduct();

            if (!$product) {
                return false;
            }
            //save variations
            $this->saveVariations($product);
            //save reviews
            $product->review()->attach($this->saveReviews());
            //attach category
            $product->category()->attach($this->category, ['is_primary' => 1]);
            /* attach tags */
            $product->tag()->sync($tags);
            //save images
            $this->saveImages($product);

            return $product;
        } catch (\Exception $exception) {
            Log::error("Product Import: " . $exception->getMessage());
            return false;
        }
    }

    protected function saveTags($tags)
    {
        $result = [];
        $tags = explode(',', $tags);
        $tags = array_filter($tags);

        if (empty($tags))
            return $result;

        foreach ($tags as $tag) {
            $tag = trim($tag);
            $find = Tag::withoutGlobalScopes()->where('slug', $tag)->first();

            if (is_null($find) && !empty($tag)) {

                $find = Tag::create([
                    'slug' => $tag,
                ]);
            }

            $result[] = $find->id;
        }

        return $result;
    }


    protected function saveProduct()
    {
        try {
            return Product::create([
                'sp_hash' => $this->spHash ?? $this->generateHash(),
                'codes' => $this->codes(),
                'brand_id' => $this->savedBrand,
                'title' => $this->record->name,
                'description' => $this->description(),
                'weight' => substr($this->record->weight, 0, 49),
                'dimensions' => $this->record->dimension,
                'features' => (!is_null($this->record->features)) ? json_encode($this->record->features) : null,
                'manufacturer' => substr($this->record->manufacturer, 0, 149),
                'status' => 1,
            ]);
        } catch (\Exception $exception) {
            Log::error("Product Import: " . $exception->getMessage());
            return false;
        }
    }

    protected function saveImages($product)
    {
        $path = $this->imageDir . DIRECTORY_SEPARATOR . substr($product->id, 0, 4);

        $this->imageImporter()
            ->product($product)
            ->images($this->images())
            ->path($path)
            ->primaryImage($this->images()[0])
            ->save();
    }

    protected function imageImporter()
    {
        return new \App\Libraries\ProductImageImporter();
    }

    public function images()
    {
        $images = array_merge($this->record->primary_images, $this->record->images);
        return array_unique($images);
    }

    public function saveVariations($product)
    {
        foreach ($this->record->variations as $variation) {
            try {
                $source = extractDomain($variation->url)[1];
                ProductVariation::firstOrCreate([
                    'product_id' => $product->id,
                    'title' => $product->title,
                    'current_price' => $variation->price->current,
                    'currency' => $variation->price->currency,
                    'price_max' => $variation->price->max,
                    'price_min' => $variation->price->min,
                    'availability' => (boolean)$variation->availability,
                    'condition' => $variation->condition,
                    'color' => $variation->color,
                    'size' => $variation->size,
                    'merchant' => $variation->merchant,
                    'source_url' => $variation->url,
                    'source_id' => $this->saveSource($source),
                ]);
            } catch (\Exception $exception) {
                Log::error("Product variation import: " . $exception->getMessage());
            }

        }
    }

    protected function saveSource($source)
    {
        $source = trim($source);
        $find = Source::withoutGlobalScopes()->where('name', trim($source))->first();

        if (is_null($find) && !empty($source)) {
            $find = Source::create([
                'name' => trim($source),
            ]);
        }

        return $find->id;
    }

    protected function saveReviews()
    {
        $savedIndex = [];

        if (is_null($this->reviews()) || empty($this->reviews()))
            return $savedIndex;

        foreach ($this->reviews() as $review) {
            try {
                $save = Review::create([
                    'user_name' => $review->username,
                    'title' => $review->title,
                    'text' => $review->text,
                    'rating' => $review->rating,
                    'source_url' => $review->source,
                    'published_at' => $review->published_at,
                ]);

                $savedIndex[] = $save->id;
            } catch (\Exception $exception) {
                Log::error('Review import error: ' . $exception->getMessage());
            }
        }

        return $savedIndex;
    }

    public function reviews()
    {
        return $this->record->reviews;
    }

    public function getRecord()
    {
        return $this->record;
    }

    public function description()
    {
        if (!is_array($this->record->descriptions)) {
            return null;
        }

        $description = reset($this->record->descriptions);

        if (is_null(get_property($description, 'content'))) {
            return null;
        }

        return $description->content;
    }

    public function saveBrand()
    {
        if (is_null($this->brand()) || empty($this->brand())) {
            return 1;
        }
        $save = Brand::firstOrCreate(['name' => $this->brand()], [
            'parent_id' => 0,
            'name' => $this->brand(),
            'slug' => seoUrl($this->brand()),
        ]);

        $this->savedBrand = $save->id ?? 1;

        return $this->savedBrand;
    }

    public function brand()
    {
        return $this->record->brand;
    }

    public function savedBrand()
    {
        return $this->savedBrand;
    }

    public function codes()
    {
        return json_encode($this->record->codes);
    }

    public function checkDuplicate()
    {
        if ($this->codes() == $this->defaultProductCode) {
            $where = [
                array('brand_id', $this->savedBrand()),
                array('title', $this->record->name),
            ];
            $checkDuplicate = Product::withoutGlobalScopes()->where($where)->first();
        } else {
            $checkDuplicate = Product::withoutGlobalScopes()->whereLike(['codes'], $this->codes())->first();
        }
        return $checkDuplicate;
    }

    public function hash($hash)
    {
        $this->spHash = $hash;
        return $this;
    }

    private function generateHash()
    {
        return substr(base64_encode(mt_rand() . time() . mt_rand()), 1, 10);
    }
}
