<?php

namespace App\Service;

use App\Entity\File;
use App\Exception\FileStorageAccessException;
use App\Storage\UploadedFileStorage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadedFileRegistrar
{
    public function __construct(
        protected TokenGenerator $tokenGenerator,
        protected UploadedFileStorage $uploadedFileStorage,
        protected EntityManagerInterface $manager,
    ) {}

    /**
     * @throws FileStorageAccessException
     */
    public function register(UploadedFile $file, string $groupToken): File
    {
        $token = $this->tokenGenerator->generate();
        $entity = File::prepareFromUploadedFile($file);

        $entity->setToken($token);
        $entity->setGroupToken($groupToken);
        $entity->setServerFilename($token);

        $this->uploadedFileStorage->store($file, $entity);

        $this->manager->persist($entity);
        $this->manager->flush();

        return $entity;
    }
}
