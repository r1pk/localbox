<?php

namespace App\Service\FileStorage;

use App\Entity\File;
use App\Exception\FileStorageAccessException;
use App\Exception\MissingStoredFileException;
use App\Model\FileLocation\FileLocationInterface;
use App\Model\FileLocation\LocalFileLocation;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class LocalFileStorage implements FileStorageInterface
{
    public function __construct(
        #[Autowire(env: 'LOCAL_STORAGE_DIRECTORY')]
        protected string $localStorageDirectory,
        protected Filesystem $filesystem,
    ) {}

    /**
     * @throws FileStorageAccessException
     */
    public function store(UploadedFile $file, File $entity): void
    {
        try {
            $path = $this->getPath($entity);

            $file->move(
                dirname($path),
                basename($path),
            );
        } catch (FileException $exception) {
            throw new FileStorageAccessException(
                'Unable to move the uploaded file to the target destination',
                previous: $exception,
            );
        }
    }

    /**
     * @throws FileStorageAccessException
     */
    public function remove(File $entity): void
    {
        try {
            $path = $this->getPath($entity);

            if (is_file($path)) {
                $this->filesystem->remove($path);
            }
        } catch (IOException $exception) {
            throw new FileStorageAccessException(
                'Unable to remove the stored file from local storage',
                previous: $exception,
            );
        }
    }

    /**
     * @throws MissingStoredFileException
     */
    public function locate(File $entity): FileLocationInterface
    {
        $path = $this->getPath($entity);

        if (!is_file($path)) {
            throw new MissingStoredFileException(
                'File record exists but the stored file is missing from local storage',
            );
        }

        return new LocalFileLocation($path);
    }

    protected function getPath(File $entity): string
    {
        return implode(DIRECTORY_SEPARATOR, [
            $this->localStorageDirectory,
            $entity->getGroupToken(),
            $entity->getServerFilename(),
        ]);
    }
}
