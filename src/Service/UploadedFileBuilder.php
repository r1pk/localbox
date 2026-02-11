<?php

namespace App\Service;

use App\Exception\FileAvailabilityException;
use App\Exception\FileChunkIntegrityException;
use App\Exception\FileStorageAccessException;
use App\Model\ChunkedUploadRequest;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class UploadedFileBuilder
{
    public function __construct(
        protected TemporaryDirectoryPathResolver $temporaryDirectoryPathResolver,
        protected Filesystem $filesystem,
    ) {}

    /**
     * @throws FileAvailabilityException
     * @throws FileStorageAccessException
     * @throws FileChunkIntegrityException
     */
    public function build(Request $request): ?UploadedFile
    {
        $chunkedUploadRequest = new ChunkedUploadRequest($request);

        $uuid = $chunkedUploadRequest->getUuid();
        $chunk = $chunkedUploadRequest->getFile();

        if ($chunkedUploadRequest->isFirstChunk()) {
            $this->initialize($chunk, $uuid);
        } else {
            $this->append($chunk, $uuid);
        }

        if (!$chunkedUploadRequest->isLastChunk()) {
            return null;
        }

        return $this->finalize($chunk, $uuid);
    }

    /**
     * @throws FileStorageAccessException
     */
    protected function initialize(UploadedFile $chunk, string $uuid): void
    {
        try {
            $chunk->move(
                $this->temporaryDirectoryPathResolver->resolve(),
                $uuid,
            );
        } catch (FileException $exception) {
            throw new FileStorageAccessException(
                'Failed to initialize chunked upload: ' . $exception->getMessage(), previous: $exception,
            );
        }
    }

    /**
     * @throws FileChunkIntegrityException
     */
    protected function append(UploadedFile $chunk, string $uuid): void
    {
        try {
            $path = implode(DIRECTORY_SEPARATOR, [
                $this->temporaryDirectoryPathResolver->resolve(),
                $uuid,
            ]);
            $content = $chunk->getContent();

            $this->filesystem->appendToFile($path, $content);
        } catch (IOException $exception) {
            throw new FileChunkIntegrityException(
                'Failed to append chunk to existing file', previous: $exception,
            );
        }
    }

    /**
     * @throws FileAvailabilityException
     */
    protected function finalize(UploadedFile $chunk, string $uuid): ?UploadedFile
    {
        $path = implode(DIRECTORY_SEPARATOR, [
            $this->temporaryDirectoryPathResolver->resolve(),
            $uuid,
        ]);

        if (!file_exists($path)) {
            throw new FileAvailabilityException('File does not exist');
        }

        return new UploadedFile(
            $path, $chunk->getClientOriginalName(), mime_content_type($path), null, true,
        );
    }
}
