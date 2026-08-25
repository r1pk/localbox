<?php

namespace App\Service;

use App\Exception\ChunkAssemblyFailedException;
use App\Exception\FileStorageAccessException;
use App\Exception\MissingStoredFileException;
use App\Model\ChunkedUploadRequest;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class UploadedFileBuilder
{
    public function __construct(
        protected Filesystem $filesystem,
    ) {}

    /**
     * @throws MissingStoredFileException
     * @throws ChunkAssemblyFailedException
     * @throws FileStorageAccessException
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
            $path = $this->getTemporaryPath($uuid);

            $chunk->move(
                dirname($path),
                basename($path),
            );
        } catch (FileException $exception) {
            throw new FileStorageAccessException(
                'Unable to store the first chunk of a chunked upload', previous: $exception,
            );
        }
    }

    /**
     * @throws ChunkAssemblyFailedException
     */
    protected function append(UploadedFile $chunk, string $uuid): void
    {
        try {
            $path = $this->getTemporaryPath($uuid);
            $content = $chunk->getContent();

            $this->filesystem->appendToFile($path, $content);
        } catch (IOException $exception) {
            throw new ChunkAssemblyFailedException(
                'Unable to append a chunk to the assembly file', previous: $exception,
            );
        }
    }

    /**
     * @throws MissingStoredFileException
     */
    protected function finalize(UploadedFile $chunk, string $uuid): UploadedFile
    {
        $path = $this->getTemporaryPath($uuid);

        if (!file_exists($path)) {
            throw new MissingStoredFileException('Assembly file is missing after receiving the final chunk');
        }

        return new UploadedFile(
            $path, $chunk->getClientOriginalName(), mime_content_type($path) ?: null, null, true,
        );
    }

    protected function getTemporaryPath(string $uuid): string
    {
        return implode(DIRECTORY_SEPARATOR, [
            sys_get_temp_dir(),
            hash('xxh128', $uuid),
        ]);
    }
}
