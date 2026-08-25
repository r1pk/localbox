<?php

namespace App\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

readonly class ChunkedUploadRequest
{
    protected UploadedFile $file;

    protected string $uuid;

    protected int $chunkIndex;

    protected int $totalChunkCount;

    public function __construct(Request $request)
    {
        $this->file = $request->files->get('file');
        $this->uuid = $request->request->get('dzuuid');

        $this->chunkIndex = $request->request->getInt('dzchunkindex');
        $this->totalChunkCount = $request->request->getInt('dztotalchunkcount');
    }

    public function getFile(): UploadedFile
    {
        return $this->file;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getChunkIndex(): int
    {
        return $this->chunkIndex;
    }

    public function isFirstChunk(): bool
    {
        return $this->getChunkIndex() === 0;
    }

    public function isLastChunk(): bool
    {
        return $this->getChunkIndex() + 1 === $this->getTotalChunkCount();
    }

    public function getTotalChunkCount(): int
    {
        return $this->totalChunkCount;
    }
}
