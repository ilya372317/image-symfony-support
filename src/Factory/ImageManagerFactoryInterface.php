<?php

namespace Xaduken\ImageSupport\Factory;

use Xaduken\ImageSupport\Database\DatabaseImageUploaderInterface;
use Xaduken\ImageSupport\Filesystem\FilesystemUploaderInterface;

interface ImageManagerFactoryInterface
{
    public function getDatabaseUploader(): DatabaseImageUploaderInterface;

    public function getFilesystemUploader(): FilesystemUploaderInterface;
}
