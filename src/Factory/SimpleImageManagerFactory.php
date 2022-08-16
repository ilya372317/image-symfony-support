<?php

namespace App\Package\SymfonyImageSupport\Factory;

use App\Package\SymfonyImageSupport\Database\DatabaseImageUploader;
use App\Package\SymfonyImageSupport\Database\DatabaseImageUploaderInterface;
use App\Package\SymfonyImageSupport\Filesystem\FilesystemUploaderInterface;
use App\Package\SymfonyImageSupport\Filesystem\StandardFilesystemUploader;
use Doctrine\ORM\EntityManagerInterface;

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