<?php


namespace App\Generators;

use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\PathGenerator\PathGenerator;

class MediaPathGenerator implements PathGenerator
{

    public function getPath(Media $media): string
    {
        // TODO: Implement getPath() method.
        $collection = $media->collection_name;
        Storage::disk('media')->makeDirectory($collection);

        return $collection . DIRECTORY_SEPARATOR . $media->name . DIRECTORY_SEPARATOR . $media->id . DIRECTORY_SEPARATOR;
    }

    public function getPathForConversions(Media $media): string
    {
        // TODO: Implement getPathForConversions() method.
        return $this->getPath($media) . 'c' . DIRECTORY_SEPARATOR;
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        // TODO: Implement getPathForResponsiveImages() method.
        return $this->getPath($media) . DIRECTORY_SEPARATOR . 'cri' . DIRECTORY_SEPARATOR;
    }
}
