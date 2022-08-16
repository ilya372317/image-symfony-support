<?php

namespace App\Package\SymfonyImageSupport\Filesystem;

use App\Package\SymfonyImageSupport\DTO\ImageInfo;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FilesystemUploaderInterface
{
    public function upload(UploadedFile $uploadedFile, string $targetDirectory): ImageInfo;
}