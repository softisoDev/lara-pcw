<?php

namespace App\Traits;

trait MediaProperties
{
    public function getMainImage()
    {
        $images = $this->getMedia('image');

        if (is_null($images)) {
            return null;
        }

        foreach ($images as $image) {
            if ($image->hasCustomProperty('is_main')) {
                return $image;
            }
        }
        return null;
    }

    public function getMainImageUrl($thumb = null)
    {
        $image = $this->getMainImage();

        if (is_null($image)) {
            return config('constants.image.no_image');
        }

        if (!is_null($thumb)) {
            return $image->getFullUrl($thumb);
        }

        return $image->getFullUrl();
    }

    public function getMainImageProperty($property = '')
    {
        $image = $this->getMainImage();

        if (is_null($image))
            return null;

        if ($image->hasCustomProperty($property)) {
            return $image->getCustomProperty($property);
        }

        return null;
    }

}
