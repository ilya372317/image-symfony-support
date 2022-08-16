<?php

namespace Xaduken\ImageSupport\Database;

use Doctrine\ORM\EntityManagerInterface;
use Xaduken\ImageSupport\DTO\ImageInfo;
use Xaduken\ImageSupport\EntityInterface\Imageable;

class DatabaseImageUploader implements DatabaseImageUploaderInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function upload(ImageInfo $imageInfo, string $relatedEntity, string $imageClass): Imageable
    {
        $image = $this->getImageObject($imageInfo, $relatedEntity, $imageClass);
        return $this->persistImageObject($image);
    }

    private function getImageObject(ImageInfo $imageInfo, string $relatedEntity, string $imageClass): Imageable
    {
        $image = new $imageClass;
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
