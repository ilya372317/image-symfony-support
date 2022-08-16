<?php

namespace App\Package\SymfonyImageSupport\EntityInterface;

interface Imageable
{
    public function getPath(): ?string;

    public function setPath(?string $path): self;

    public function getFileName(): ?string;

    public function setFileName(string $fileName): self;

    public function getMimeType(): ?string;

    public function setMimeType(string $mimeType): self;
}