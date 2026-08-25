<?php

namespace App\Service\FileStorage;

use App\Entity\File;
use App\Model\FileLocation\FileLocationInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileStorageInterface
{
    public function store(UploadedFile $file, File $entity): void;

    public function remove(File $entity): void;

    public function locate(File $entity): FileLocationInterface;
}
