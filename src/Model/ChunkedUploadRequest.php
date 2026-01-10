<?php

namespace App\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class ChunkedUploadRequest
{
    protected UploadedFile $file;

    protected string $uuid;

    protected int $chunkIndex;

    protected int $chunkSize;

    protected int $chunkByteOffset;

    protected int $totalChunkCount;

    protected int $totalFileSize;

    public function __construct(Request $request)
    {
        $this->file = $request->files->get('file');
        $this->uuid = $request->request->get('dzuuid');

        $this->chunkIndex = $request->request->getInt('dzchunkindex');
        $this->chunkSize = $request->request->getInt('dzchunksize');
        $this->chunkByteOffset = $request->request->getInt('dzchunkbyteoffset');
        $this->totalChunkCount = $request->request->getInt('dztotalchunkcount');
        $this->totalFileSize = $request->request->getInt('dztotalfilesize');
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

    public function getChunkSize(): int
    {
        if ($this->isLastChunk()) {
            return $this->getLastChunkSize();
        }

        return $this->chunkSize;
    }

    public function getLastChunkSize(): int
    {
        return $this->getTotalFileSize() % $this->chunkSize;
    }

    public function getChunkByteOffset(): int
    {
        return $this->chunkByteOffset;
    }

    public function getTotalChunkCount(): int
    {
        return $this->totalChunkCount;
    }

    public function getTotalFileSize(): int
    {
        return $this->totalFileSize;
    }
}
