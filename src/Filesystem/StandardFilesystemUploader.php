<?php

namespace App\Package\SymfonyImageSupport\Filesystem;

use App\Package\SymfonyImageSupport\DTO\ImageInfo;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Log\Logger;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\Slugger\SluggerInterface;

class StandardFilesystemUploader implements FilesystemUploaderInterface
{
    public function upload(UploadedFile $uploadedFile, string $targetDirectory): ImageInfo
    {
        $filename = $this->generateFilename($uploadedFile);
        $mimeType = $uploadedFile->getMimeType();

        try {
            $uploadedFile->move(
                $targetDirectory,
                $filename
            );
        } catch (FileException) {
            $logger = new Logger();
            $logger->warning('failed to save file '.$filename);
        }

        return new ImageInfo($filename, $targetDirectory, $mimeType);
    }

    private function getSlugger(): SluggerInterface
    {
        return new AsciiSlugger();
    }

    private function generateFilename(UploadedFile $uploadedFile): string
    {
        $slugger = $this->getSlugger();
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($originalFilename);
        return $safeFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
    }
}