<?php

namespace App\Service;

use App\Entity\File;
use App\Exception\FileAvailabilityException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class FileDownloader
{
    public function __construct(
        protected FileLocationProvider $fileLocationProvider,
    ) {}

    /**
     * @throws FileAvailabilityException
     */
    public function createBinaryFileResponse(File $file): BinaryFileResponse
    {
        $path = $this->fileLocationProvider->getPath($file);

        if (!file_exists($path)) {
            throw new FileAvailabilityException('File does not exist');
        }

        $response = new BinaryFileResponse($path);

        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $file->getClientFilename(),
        );

        return $response;
    }
}
