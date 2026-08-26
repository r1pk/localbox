<?php

namespace App\Model\UploadRequest;

use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class DirectUploadRequest implements UploadRequestInterface
{
    public function __construct(
        protected string $groupToken,
        protected UploadedFile $payload,
    ) {}

    public function getGroupToken(): string
    {
        return $this->groupToken;
    }

    public function getPayload(): UploadedFile
    {
        return $this->payload;
    }
}
