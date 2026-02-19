<?php

namespace App\Service;

use App\Entity\File;
use App\Exception\FileStorageAccessException;
use App\Storage\UploadedFileStorage;
use Doctrine\ORM\EntityManagerInterface;

class FileDeleter
{
    public function __construct(
        protected UploadedFileStorage $uploadedFileStorage,
        protected EntityManagerInterface $manager,
    ) {}

    /**
     * @throws FileStorageAccessException
     */
    public function delete(File $file): void
    {
        $this->uploadedFileStorage->remove($file);

        $this->manager->remove($file);
        $this->manager->flush();
    }
}
