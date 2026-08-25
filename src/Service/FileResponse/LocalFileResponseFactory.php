<?php

namespace App\Service\FileResponse;

use App\Model\FileLocation\FileLocationInterface;
use App\Model\FileLocation\LocalFileLocation;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class LocalFileResponseFactory implements FileResponseFactoryInterface
{
    public function supports(FileLocationInterface $location): bool
    {
        return $location instanceof LocalFileLocation;
    }

    public function create(FileLocationInterface $location, string $filename): Response
    {
        $response = new BinaryFileResponse((string) $location);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename,
        );

        return $response;
    }
}
