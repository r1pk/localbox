<?php

namespace App\Service;

use App\Entity\File;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class FileLocationProvider
{
    public function __construct(
        #[Autowire(env: 'UPLOAD_DIRECTORY')]
        protected string $uploadDirectory,
    ) {}

    public function getPath(File $file): string
    {
        return implode(DIRECTORY_SEPARATOR, [
            $this->getDirectoryPath($file),
            $file->getServerFilename()
        ]);
    }

    public function getDirectoryPath(File $file): string
    {
        return implode(DIRECTORY_SEPARATOR, [
            $this->uploadDirectory,
            $file->getDirectory()
        ]);
    }

    public function getTemporaryPath(string $filename): string
    {
        return implode(DIRECTORY_SEPARATOR, [
            $this->getTemporaryDirectoryPath(),
            $filename,
        ]);
    }

    public function getTemporaryDirectoryPath(): string
    {
        return sys_get_temp_dir();
    }
}
