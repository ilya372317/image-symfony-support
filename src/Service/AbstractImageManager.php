<?php

namespace App\Package\SymfonyImageSupport\Service;

use App\Package\SymfonyImageSupport\Database\DatabaseImageUploaderInterface;
use App\Package\SymfonyImageSupport\DTO\ImageInfo;
use App\Package\SymfonyImageSupport\EntityInterface\Imageable;
use App\Package\SymfonyImageSupport\Factory\ImageManagerFactoryInterface;
use App\Package\SymfonyImageSupport\Filesystem\FilesystemUploaderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

abstract class AbstractImageManager
{
    public function save(UploadedFile $uploadedFile): Imageable
    {
        $imageManagerFactory = $this->getImageManagerFactory();
        $imageInfo = $this->uploadToFilesystem($imageManagerFactory->getFilesystemUploader(), $uploadedFile);
        return $this->uploadToDatabase($imageManagerFactory->getDatabaseUploader(), $imageInfo);
    }

    abstract protected function getTargetDirectory(): string;

    abstract protected function getRelatedClass(): string;

    abstract protected function getImageManagerFactory(): ImageManagerFactoryInterface;

    abstract protected function getEntityManager(): EntityManagerInterface;

    private function uploadToFilesystem(
        FilesystemUploaderInterface $filesystemUploader,
        UploadedFile $uploadedFile
    ): ImageInfo {
        return $filesystemUploader->upload($uploadedFile, $this->getTargetDirectory());
    }

    private function uploadToDatabase(
        DatabaseImageUploaderInterface $databaseImageUploader,
        ImageInfo $imageInfo
    ): Imageable {
        return $databaseImageUploader->upload($imageInfo, $this->getRelatedClass());
    }

}