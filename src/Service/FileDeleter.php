<?php

namespace App\Service;

use App\Entity\File;
use App\Service\FileStorage\FileStorageResolver;
use Doctrine\ORM\EntityManagerInterface;

class FileDeleter
{
    public function __construct(
        protected FileStorageResolver $fileStorageResolver,
        protected EntityManagerInterface $manager,
    ) {}

    public function delete(File $file): void
    {
        $this->fileStorageResolver->resolve()->remove($file);

        $this->manager->remove($file);
        $this->manager->flush();
    }
}
