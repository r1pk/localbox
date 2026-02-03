<?php

namespace App\Model;

use App\Entity\File;

class FileUploadResult
{
    public function __construct(
        protected bool $isComplete,
        protected ?File $file = null,
        protected ?string $groupToken = null,
    ) {}

    public function isComplete(): bool
    {
        return $this->isComplete;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function getGroupToken(): ?string
    {
        return $this->groupToken;
    }
}
