<?php

namespace Xaduken\ImageSupport\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Xaduken\ImageSupport\Database\DatabaseImageUploaderInterface;
use Xaduken\ImageSupport\DTO\ImageInfo;
use Xaduken\ImageSupport\EntityInterface\Imageable;
use Xaduken\ImageSupport\Factory\ImageManagerFactoryInterface;
use Xaduken\ImageSupport\Filesystem\FilesystemUploaderInterface;

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

    abstract protected function getImageEntityClass(): string;

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
        return $databaseImageUploader->upload($imageInfo, $this->getRelatedClass(), $this->getImageEntityClass());
    }

}
