<?php

namespace App\Package\SymfonyImageSupport\Database;

use App\Entity\Image;
use App\Package\SymfonyImageSupport\DTO\ImageInfo;
use App\Package\SymfonyImageSupport\EntityInterface\Imageable;
use Doctrine\ORM\EntityManagerInterface;

class DatabaseImageUploader implements DatabaseImageUploaderInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function upload(ImageInfo $imageInfo, string $relatedEntity): Imageable
    {
        $image = $this->getImageObject($imageInfo, $relatedEntity);
        return $this->persistImageObject($image);
    }

    private function getImageObject(ImageInfo $imageInfo, string $relatedEntity): Imageable
    {
        $image = new Image();
        $image->setFileName($imageInfo->getFilename());
        $image->setPath($imageInfo->getTargetPath());
        $image->setMimeType($imageInfo->getMimeType());
        $image->setRelatedEntity($relatedEntity);

        return $image;
    }

    private function persistImageObject(Imageable $image): Imageable
    {
        $this->entityManager->persist($image);
        $this->entityManager->flush();

        return $image;
    }
}