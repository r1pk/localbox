<?php

namespace App\Model;

class FileUploadResult
{
    protected bool $isComplete;

    protected ?string $groupToken = null;

    public function __construct(bool $isComplete, ?string $groupToken = null)
    {
        $this->isComplete = $isComplete;
        $this->groupToken = $groupToken;
    }

    public function isComplete(): bool
    {
        return $this->isComplete;
    }

    public function getGroupToken(): ?string
    {
        return $this->groupToken;
    }
}
