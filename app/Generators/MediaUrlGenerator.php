<?php

namespace App\Generators;

use DateTimeInterface;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\UrlGenerator\BaseUrlGenerator;

class MediaUrlGenerator extends BaseUrlGenerator
{

    /**
     * @inheritDoc
     */
    public function getUrl(): string
    {
        // TODO: Implement getUrl() method.
        return url(env('UPLOAD_DIR')) . DIRECTORY_SEPARATOR . $this->getPathRelativeToRoot();
    }

    public function getPath()
    {
        return Storage::disk('media')->getDriver()->getAdapter()->getPathPrefix().$this->getPathRelativeToRoot();
    }

    /**
     * @inheritDoc
     */
    public function getTemporaryUrl(DateTimeInterface $expiration, array $options = []): string
    {
        // TODO: Implement getTemporaryUrl() method.
        return url('/') . DIRECTORY_SEPARATOR . env('UPLOAD_DIR') . DIRECTORY_SEPARATOR;
    }

    /**
     * @inheritDoc
     */
    public function getResponsiveImagesDirectoryUrl(): string
    {
        // TODO: Implement getResponsiveImagesDirectoryUrl() method.
        return url('/') . DIRECTORY_SEPARATOR . env('UPLOAD_DIR') . DIRECTORY_SEPARATOR;
    }
}
