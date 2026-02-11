<?php

namespace App\Storage;

use App\Entity\File;
use App\Exception\FileStorageAccessException;
use App\Service\UploadDirectoryPathResolver;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadedFileStorage
{
    public function __construct(
        protected UploadDirectoryPathResolver $uploadDirectoryPathResolver,
    ) {}

    /**
     * @throws FileStorageAccessException
     */
    public function store(UploadedFile $file, File $entity): void
    {
        try {
            $filename = $entity->getServerFilename();
            $path = $this->uploadDirectoryPathResolver->resolve($entity);

            $file->move($path, $filename);
        } catch (FileException $exception) {
            throw new FileStorageAccessException(
                'Unable to move the uploaded file to the target destination', previous: $exception,
            );
        }
    }
}
