<?php

namespace App\Models;

use App\Traits\ModelCache;
use App\Traits\TotalCounter;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read  int $review_count
 * @property-read  double $review_rating
 */
class Review extends Model
{
    use ModelCache;
    use TotalCounter;

    public const CACHE_NAME = [
        'total' => 'total',
    ];

    protected $table = 'reviews';

    protected $fillable = [
        'email', 'user_name', 'title', 'text', 'num_helpful', 'rating', 'source_url'
    ];

    protected $casts = [
        'review_rating' => 'double'
    ];

    public function product()
    {
        return $this->belongsToMany(Product::class, 'product_review', 'review_id', 'product_id');
    }

    public static function loadMore($productId, $skip = 10, $take = 10)
    {
        return self::query()
            ->whereHas('product', function ($query) use ($productId) {
                $query->where('id', $productId);
            })
            ->skip($skip)
            ->take($take)
            ->get([
                'id',
                'title',
                'rating',
                'text',
                'published_at',
                'user_name',
                'created_at',
            ]);
    }
}
