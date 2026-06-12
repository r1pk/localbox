<?php

namespace App\Service\Response;

use App\Entity\File;
use App\Exception\FileAvailabilityException;
use App\Service\Path\UploadDirectoryPathResolver;
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
    public function fromFile(File $file): BinaryFileResponse
    {
        $filename = $file->getClientFilename();
        $path = implode(DIRECTORY_SEPARATOR, [
            $this->uploadDirectoryPathResolver->resolve($file),
            $file->getServerFilename()
        ]);

        return $this->fromPath($path, $filename);
    }

    /**
     * @throws FileAvailabilityException
     */
    public function fromPath(string $path, string $filename): BinaryFileResponse
    {
        if (!file_exists($path)) {
            throw new FileAvailabilityException('File does not exist');
        }

        $response = new BinaryFileResponse($path);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename);

        return $response;
    }
}
