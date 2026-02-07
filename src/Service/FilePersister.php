<?php

namespace App\Service;

use App\Entity\File;
use App\Exception\FileStorageAccessException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FilePersister
{
    public function __construct(
        protected FileLocationProvider $fileLocationProvider,
        protected EntityManagerInterface $manager,
    ) {}

    /**
     * @throws FileStorageAccessException
     */
    public function persist(UploadedFile $file, ?string $groupToken): File
    {
        $entity = File::prepareForUploadedFile($file, $groupToken);

        $this->moveUploadedFile($file, $entity);

        $this->manager->persist($entity);
        $this->manager->flush();

        return $entity;
    }

    /**
     * @throws FileStorageAccessException
     */
    protected function moveUploadedFile(UploadedFile $file, File $entity): void
    {
        try {
            $filename = $entity->getServerFilename();
            $path = $this->fileLocationProvider->getDirectoryPath($entity);

            $file->move($path, $filename);
        } catch (FileException $exception) {
            throw new FileStorageAccessException(
                'Unable to move the uploaded file to the target destination', previous: $exception,
            );
        }
    }
}
