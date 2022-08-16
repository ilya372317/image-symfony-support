<?php

namespace App\Package\SymfonyImageSupport\DTO;

class ImageInfo
{
    private string $filename;

    private string $targetPath;

    private string $mimeType;

    public function __construct(string $filename, string $targetPath, string $mimeType)
    {
        $this->filename = $filename;
        $this->targetPath = $targetPath;
        $this->mimeType = $mimeType;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @param  string  $filename
     * @return ImageInfo
     */
    public function setFilename(string $filename): self
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @return string
     */
    public function getTargetPath(): string
    {
        return $this->targetPath;
    }

    /**
     * @param  string  $targetPath
     * @return ImageInfo
     */
    public function setTargetPath(string $targetPath): self
    {
        $this->targetPath = $targetPath;
        return $this;
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * @param  string  $mimeType
     * @return ImageInfo
     */
    public function setMimeType(string $mimeType): self
    {
        $this->mimeType = $mimeType;
        return $this;
    }
}
