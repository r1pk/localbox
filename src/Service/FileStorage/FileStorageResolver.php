<?php

namespace App\Service\FileStorage;

class FileStorageResolver
{
    public function __construct(
        protected LocalFileStorage $localFileStorage,
    ) {}

    public function resolve(): FileStorageInterface
    {
        return $this->localFileStorage;
    }
}
