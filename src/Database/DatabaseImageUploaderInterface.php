<?php

namespace App\Package\SymfonyImageSupport\Database;

use App\Package\SymfonyImageSupport\DTO\ImageInfo;
use App\Package\SymfonyImageSupport\EntityInterface\Imageable;

interface DatabaseImageUploaderInterface
{
    public function upload(ImageInfo $imageInfo, string $relatedEntity): Imageable;
}