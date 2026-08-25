<?php

namespace App\Model;

readonly class FileUploadResult
{
    public function __construct(
        protected bool $isComplete,
        protected string $groupToken,
    ) {}

    public function isComplete(): bool
    {
        return $this->isComplete;
    }

    public function getGroupToken(): string
    {
        return $this->groupToken;
    }
}
