<?php

namespace Xaduken\ImageSupport\Database;

use Xaduken\ImageSupport\DTO\ImageInfo;
use Xaduken\ImageSupport\EntityInterface\Imageable;

interface DatabaseImageUploaderInterface
{
    public function upload(ImageInfo $imageInfo, string $relatedEntity, string $imageClass): Imageable;
}
