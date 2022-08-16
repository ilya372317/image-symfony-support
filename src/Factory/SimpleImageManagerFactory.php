<?php

namespace Xaduken\ImageSupport\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Xaduken\ImageSupport\Database\DatabaseImageUploader;
use Xaduken\ImageSupport\Database\DatabaseImageUploaderInterface;
use Xaduken\ImageSupport\Filesystem\FilesystemUploaderInterface;
use Xaduken\ImageSupport\Filesystem\StandardFilesystemUploader;

class SimpleImageManagerFactory implements ImageManagerFactoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getDatabaseUploader(): DatabaseImageUploaderInterface
    {
        return new DatabaseImageUploader($this->entityManager);
    }

    public function getFilesystemUploader(): FilesystemUploaderInterface
    {
        return new StandardFilesystemUploader();
    }
}
