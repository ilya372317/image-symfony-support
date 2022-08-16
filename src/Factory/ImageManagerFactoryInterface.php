<?php

namespace App\Package\SymfonyImageSupport\Factory;

use App\Package\SymfonyImageSupport\Database\DatabaseImageUploaderInterface;
use App\Package\SymfonyImageSupport\Filesystem\FilesystemUploaderInterface;

interface ImageManagerFactoryInterface
{
    public function getDatabaseUploader(): DatabaseImageUploaderInterface;

    public function getFilesystemUploader(): FilesystemUploaderInterface;
}