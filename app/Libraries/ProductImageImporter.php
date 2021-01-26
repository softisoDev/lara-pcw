<?php


namespace App\Libraries;


use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\Exceptions\FileCannotBeAdded;

class ProductImageImporter
{

    private $product;
    private $images = [];
    private $path;
    private $primaryImage;
    private $collection = 'image';


    public function product($product)
    {
        $this->product = $product;
        return $this;
    }

    public function path($path)
    {
        $this->path = $path;
        return $this;
    }

    public function primaryImage($primaryImage)
    {
        $this->primaryImage = $primaryImage;
        return $this;
    }

    public function collection($collection)
    {
        $this->collection = $collection;
        return $this;
    }

    public function images($images)
    {
        $this->images = $images;
        return $this;
    }

    public function save()
    {
        foreach ($this->images as $image) {

            if ( $this->primaryImage == $image ) {
                $properties['is_main'] = true;
            } else {
                $properties = [];
            }

            $url = $image;

            /*if ( !strpos($url, 'encrypted-tbn0.gstatic.com') ) {
                $url = remove_query_arg($url);
            }*/

            try {
                $this->product->addMediaFromUrl($url)->usingFileName(generateImageNameByUrl($url))->usingName($this->path)->withCustomProperties($properties)->toMediaCollection($this->collection);
                $this->product->status = 1;
                $this->product->save();
                break;
            } catch (FileCannotBeAdded $exception) {
                Log::error('Image importer: ' . $exception->getMessage());
            }
        }
    }
}
