<?php

namespace App\Service;

use App\Entity\File;
use App\Exception\FileRelocationException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

class FilePersister
{
    public function __construct(
        #[Autowire(env: 'FILE_STORAGE_PATH')]
        protected string $fileStoragePath,
    ) {}

    /**
     * @throws FileRelocationException
     */
    public function persist(UploadedFile $file, ?string $batchUploadToken): File
    {
        $internalFilename = $this->generateInternalFilename($file);
        $internalFileStoragePath = $this->generateFileStoragePath($internalFilename);

        $this->moveUploadedFile($file, $internalFileStoragePath);

        return $this->createFileEntity($internalFilename, $internalFileStoragePath, $batchUploadToken);
    }

    protected function generateInternalFilename(UploadedFile $file): string
    {
        return Uuid::v4()->toRfc4122() . '.' . $file->guessExtension();
    }

    protected function generateFileStoragePath(string $filename): string
    {
        return implode(DIRECTORY_SEPARATOR, [
            $this->fileStoragePath,
            date('Y'),
            date('m'),
            $filename,
        ]);
    }

    /**
     * @throws FileRelocationException
     */
    protected function moveUploadedFile(UploadedFile $file, string $path): void
    {
        try {
            $file->move(dirname($path), basename($path));
        } catch (FileException $exception) {
            throw new FileRelocationException(
                'Unable to move the uploaded file to the target destination', previous: $exception,
            );
        }
    }

    protected function createFileEntity(string $filename, string $path, ?string $batchUploadToken): File
    {
        $entity = new File();
        $uuid = Uuid::v4()->toRfc4122();

        $entity->setToken($uuid);
        $entity->setServerFilename($filename);
        $entity->setStoragePath($path);
        $entity->setBatchUploadToken($batchUploadToken);

        return $entity;
    }
}
