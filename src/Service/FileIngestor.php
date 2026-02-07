<?php

namespace App\Service;

use App\Exception\FileAvailabilityException;
use App\Exception\FileChunkIntegrityException;
use App\Exception\FileChunkValidationException;
use App\Exception\FileStorageAccessException;
use App\Model\ChunkedUploadRequest;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class FileIngestor
{
    protected Filesystem $filesystem;

    protected string $temporaryUploadDirectory;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        $this->temporaryUploadDirectory = sys_get_temp_dir();
    }

    /**
     * @throws FileAvailabilityException
     * @throws FileStorageAccessException
     * @throws FileChunkIntegrityException
     * @throws FileChunkValidationException
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
     * @throws FileAvailabilityException
     * @throws FileChunkIntegrityException
     * @throws FileStorageAccessException
     * @throws FileChunkValidationException
     */
    protected function handleChunkedUpload(Request $request): ?UploadedFile
    {
        $chunkedUploadRequest = new ChunkedUploadRequest($request);

        $filename = $chunkedUploadRequest->getUuid();
        $chunk = $chunkedUploadRequest->getFile();

        if ($chunkedUploadRequest->getChunkSize() !== $chunk->getSize()) {
            throw new FileChunkValidationException('Chunk size does not match the requested chunk size');
        }

        if ($chunkedUploadRequest->isFirstChunk()) {
            $this->initializeUpload($chunk, $filename);
        } else {
            $this->appendChunk($chunk, $filename);

            if ($chunkedUploadRequest->isLastChunk()) {
                return $this->finalizeUpload($filename, $chunk->getClientOriginalName());
            }
        }

        return null;
    }

    /**
     * @throws FileStorageAccessException
     */
    protected function initializeUpload(UploadedFile $chunk, string $filename): void
    {
        try {
            $chunk->move($this->temporaryUploadDirectory, $filename);
        } catch (FileException $exception) {
            throw new FileStorageAccessException(
                'Failed to initialize chunked upload: ' . $exception->getMessage(), previous: $exception,
            );
        }
    }

    /**
     * @throws FileChunkIntegrityException
     */
    protected function appendChunk(UploadedFile $chunk, string $filename): void
    {
        try {
            $this->filesystem->appendToFile(
                $this->composeAbsolutePath($filename),
                $chunk->getContent(),
            );
        } catch (IOException $exception) {
            throw new FileChunkIntegrityException(
                'Failed to append chunk to existing file', previous: $exception,
            );
        }
    }

    /**
     * @throws FileAvailabilityException
     */
    protected function finalizeUpload(string $filename, string $clientOriginalName): UploadedFile
    {
        $path = $this->composeAbsolutePath($filename);

        if (!file_exists($path)) {
            throw new FileAvailabilityException('File does not exist');
        }

        return new UploadedFile(
            $path, $clientOriginalName, mime_content_type($path), null, true,
        );
    }

    protected function composeAbsolutePath(string $filename): string
    {
        return implode(DIRECTORY_SEPARATOR, [$this->temporaryUploadDirectory, $filename]);
    }
}
