<?php


namespace App\Helpers;


use App\Models\Product;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use Illuminate\Support\Carbon;

class Schema
{
    public static function singleProduct($product)
    {
        $cacheKey = sprintf(Product::CACHE_NAME['schema'], $product->sp_hash);

        return Product::cacheStore($cacheKey, Carbon::now()->addMonths(), function () use ($product) {
            $json = [
                "@context" => "http://schema.org",
                "@type"    => "Product",
                "sku"      => $product->sp_hash,
            ];

            if ($product->review_count > 0) {
                $json["aggregateRating"] = [
                    "@type"       => "AggregateRating",
                    "ratingValue" => $product->reviewDetail->average,
                    "reviewCount" => $product->reviewDetail->aggregate,
                ];
                $json["review"] = self::prepareReview($product->review->take(5));
            }


            if (!is_null($product->codes["gtins"])) $json["gtin"] = (is_array($product->codes["gtins"])) ? $product->codes["gtins"] : explode(",", $product->codes["gtins"]);
            if (!is_null($product->manufacturer) && !empty($product->manufacturer)) $json["manufacturer"] = $product->manufacturer;
            if (!is_null($product->weight) && !empty($product->weight)) $json["weight"] = $product->weight;
            if (!is_null($product->variations->first()->color) && !empty($product->variations->first()->color)) $json["color"] = $product->variations->first()->color;

            $json["description"] = $product->description;
            $json["name"] = $product->title;
            $json["url"] = $product->generateUrl();
            $json["image"] = self::prepareImage($product->media);
            $json["category"] = $product->main_category->name;
            $json["brand"] = $product->brand->name ?? "";
            $json["productID"] = $product->sp_hash;

            $json["offers"] = [
                "@type"         => "AggregateOffer",
                "lowPrice"      => $product->variations->first()->current_price,
                "highPrice"     => $product->variations->last()->current_price,
                "priceCurrency" => $product->variations->first()->currency,
                "offerCount"    => count($product->variations),
                "offers"        => self::prepareOffer($product->variations)
            ];

            return json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        });
    }

    public static function categoryBreadcrumb($category)
    {
        $breadcrumbs = Breadcrumbs::generate('category', $category);

        $json = [
            "@context" => "http://schema.org",
            "@type"    => "BreadcrumbList",
        ];

        foreach ($breadcrumbs as $key => $breadcrumb) {
            $json['itemListElement'][] = [
                "@type"    => "ListItem",
                "position" => (int)$key + 1,
                "item"     => [
                    "@id"  => !is_null($breadcrumb->url) ? addSlash2Url($breadcrumb->url) : addSlash2Url($category->generateUrl()),
                    "name" => $breadcrumb->title,
                ]
            ];
        }

        return json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public static function productBreadcrumb($category, $product)
    {
        $breadcrumbs = Breadcrumbs::generate('single_product_category', $category, $product);
        $json = [
            "@context" => "http://schema.org",
            "@type"    => "BreadcrumbList",
        ];

        foreach ($breadcrumbs as $key => $breadcrumb) {
            $json['itemListElement'][] = [
                "@type"    => "ListItem",
                "position" => (int)$key + 1,
                "item"     => [
                    "@id"  => !is_null($breadcrumb->url) ? addSlash2Url($breadcrumb->url) : $product->generateUrl(),
                    "name" => $breadcrumb->title,
                ]
            ];
        }

        return json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    private static function prepareOffer($offers)
    {
        $result = [];
        foreach ($offers as $offer) {
            $result[] = [
                "@type"         => "Offer",
                "availability"  => $offer->availability,
                "price"         => $offer->current_price,
                "priceCurrency" => $offer->currency,
                'url'           => $offer->source_url,
            ];
        }

        return $result;
    }

    private static function prepareReview($reviews)
    {
        $result = [];
        foreach ($reviews as $review) {
            $temp = [
                "@type" => "Review",
            ];
            if (!empty($review->user_name) && !is_null($review->user_name)) $temp["author"] = $review->user_name;
            if (!empty($review->title) && !is_null($review->title)) $temp["text"] = $review->title;
            $temp["description"] = $review->text;
            $temp["reviewRating"] = [
                "ratingValue" => $review->rating,
                "bestRating"  => 5,
                "worstRating" => 1,
            ];

            $result[] = $temp;
            unset($temp);
        }

        return $result;
    }

    private static function prepareImage($images)
    {
        $result = [];
        foreach ($images as $image) {
            $result[] = $image->getFullUrl();
        }

        return $result;
    }


}
