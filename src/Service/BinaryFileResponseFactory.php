<?php

namespace App\Service;

use App\Entity\File;
use App\Exception\FileAvailabilityException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class BinaryFileResponseFactory
{
    public function __construct(
        protected UploadDirectoryPathResolver $uploadDirectoryPathResolver,
    ) {}

    /**
     * @throws FileAvailabilityException
     */
    public function create(File $file): BinaryFileResponse
    {
        $filename = $file->getClientFilename();
        $path = implode(DIRECTORY_SEPARATOR, [
            $this->uploadDirectoryPathResolver->resolve($file),
            $file->getServerFilename()
        ]);

        if (!file_exists($path)) {
            throw new FileAvailabilityException('File does not exist');
        }

        $response = new BinaryFileResponse($path);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename);

        return $response;
    }
}
