<?php

namespace App\Service;

use App\Entity\File;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class UploadDirectoryPathResolver
{
    public function __construct(
        #[Autowire(env: 'UPLOAD_DIRECTORY')]
        protected string $uploadDirectory,
    ) {}

    public function resolve(File $file): string
    {
        return implode(DIRECTORY_SEPARATOR, [
            $this->uploadDirectory,
            $file->getGroupToken()
        ]);
    }
}
