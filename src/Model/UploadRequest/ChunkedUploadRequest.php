<?php

namespace App\Model\UploadRequest;

use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class ChunkedUploadRequest implements UploadRequestInterface
{
    public function __construct(
        protected string $uuid,

        protected int $chunkIndex,
        protected int $totalChunkCount,

        protected string $groupToken,
        protected UploadedFile $payload,
    ) {}

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function isFirstChunk(): bool
    {
        return $this->getChunkIndex() === 0;
    }

    public function isLastChunk(): bool
    {
        return $this->getChunkIndex() + 1 === $this->getTotalChunkCount();
    }

    public function getChunkIndex(): int
    {
        return $this->chunkIndex;
    }

    public function getTotalChunkCount(): int
    {
        return $this->totalChunkCount;
    }

    public function getGroupToken(): string
    {
        return $this->groupToken;
    }

    public function getPayload(): UploadedFile
    {
        return $this->payload;
    }
}
