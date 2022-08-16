<?php

namespace Xaduken\ImageSupport\Filesystem;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Xaduken\ImageSupport\DTO\ImageInfo;

interface FilesystemUploaderInterface
{
    public function upload(UploadedFile $uploadedFile, string $targetDirectory): ImageInfo;
}
