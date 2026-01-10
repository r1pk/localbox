<?php

namespace App\Service;

use App\Exception\ChunkAssemblyException;
use App\Exception\ChunkStorageException;
use App\Exception\ChunkValidationException;
use App\Model\ChunkedUploadRequest;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class FileIngestor
{
    public function __construct(
        protected Filesystem $filesystem,
    ) {}

    /**
     * @throws ChunkAssemblyException
     * @throws ChunkStorageException
     * @throws ChunkValidationException
     */
    public function handle(Request $request): ?UploadedFile
    {
        if ($this->isChunkedUploadRequest($request)) {
            return $this->handleChunkedUpload($request);
        }

        return $request->files->get('file');
    }

    protected function isChunkedUploadRequest(Request $request): bool
    {
        return $request->request->get('dzuuid') !== null;
    }

    /**
     * @throws ChunkAssemblyException
     * @throws ChunkStorageException
     * @throws ChunkValidationException
     */
    protected function handleChunkedUpload(Request $request): ?UploadedFile
    {
        $chunkedUploadRequest = new ChunkedUploadRequest($request);
        $chunk = $chunkedUploadRequest->getFile();

        if ($chunkedUploadRequest->getChunkSize() !== $chunk->getSize()) {
            throw new ChunkValidationException('Chunk size does not match the requested chunk size');
        }

        $uuid = $chunkedUploadRequest->getUuid();
        $path = $this->generateTemporaryFilePath($uuid);

        if ($chunkedUploadRequest->isFirstChunk()) {
            $this->initializeChunkedUpload($chunk, $path);
        } else {
            $this->appendUploadedChunk($chunk, $path);

            if ($chunkedUploadRequest->isLastChunk()) {
                return $this->finalizeChunkedUpload($path, $chunk->getClientOriginalName());
            }
        }

        return null;
    }

    protected function generateTemporaryFilePath(string $uuid): string
    {
        return implode(DIRECTORY_SEPARATOR, [sys_get_temp_dir(), $uuid]);
    }

    /**
     * @throws ChunkAssemblyException
     */
    protected function initializeChunkedUpload(UploadedFile $chunk, string $path): void
    {
        try {
            $chunk->move(dirname($path), basename($path));
        } catch (FileException $exception) {
            throw new ChunkAssemblyException(
                'Failed to initialize chunked upload: ' . $exception->getMessage(), previous: $exception,
            );
        }
    }

    /**
     * @throws ChunkAssemblyException
     */
    protected function appendUploadedChunk(UploadedFile $chunk, string $path): void
    {
        try {
            $this->filesystem->appendToFile($path, $chunk->getContent());
        } catch (IOException $exception) {
            throw new ChunkAssemblyException(
                'Failed to append chunk to existing file', previous: $exception,
            );
        }
    }

    /**
     * @throws ChunkStorageException
     */
    protected function finalizeChunkedUpload(string $path, string $clientOriginalName): UploadedFile
    {
        if (!file_exists($path)) {
            throw new ChunkStorageException(
                'File does not exist',
            );
        }

        return new UploadedFile(
            $path, $clientOriginalName, mime_content_type($path), null, true,
        );
    }
}
