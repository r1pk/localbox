<?php

namespace App\Service;

use App\Entity\File;
use App\Service\FileStorage\FileStorageResolver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadedFilePersister
{
    public function __construct(
        protected TokenGenerator $tokenGenerator,
        protected FileStorageResolver $fileStorageResolver,
        protected EntityManagerInterface $manager,
    ) {}

    public function persist(UploadedFile $file, string $groupToken): File
    {
        $entity = $this->createFileEntity($file, $groupToken);

        $this->fileStorageResolver->resolve()->store($file, $entity);

        $this->manager->persist($entity);
        $this->manager->flush();

        return $entity;
    }

    protected function createFileEntity(UploadedFile $file, string $groupToken): File
    {
        $entity = new File();
        $token = $this->tokenGenerator->generate();

        $entity->setToken($token);
        $entity->setGroupToken($groupToken);

        $entity->setClientFilename($file->getClientOriginalName());
        $entity->setServerFilename($token);

        $entity->setSize($file->getSize());

        return $entity;
    }
}
