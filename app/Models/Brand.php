<?php

namespace App\Models;

use App\Traits\ModelCache;
use App\Traits\TotalCounter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Brand extends Model implements HasMedia
{
    use HasMediaTrait;
    use SoftDeletes;
    use NodeTrait;
    use TotalCounter;
    use ModelCache;

    public const CACHE_NAME = [
        'total' => 'total',
    ];

    protected $table = "brands";

    protected $fillable = [
        'parent_id', 'name', 'subtitle', 'description', 'slug', 'media_id'
    ];


    public function scopeSelectBox($query, $prependValue = "Parent brand", $prependKey = "")
    {
        return Brand::all(['name', 'id'])->pluck('name', ['id'])->sortBy('name')->prepend($prependValue, $prependKey);
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'model');
    }

    public function product()
    {
        return $this->hasMany(Product::class, 'brand_id', 'id');
    }
}
