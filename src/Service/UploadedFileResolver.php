<?php

namespace App\Service;

use App\Exception\FileAvailabilityException;
use App\Exception\FileChunkIntegrityException;
use App\Exception\FileStorageAccessException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class UploadedFileResolver
{
    public function __construct(
        protected UploadedFileBuilder $uploadedFileBuilder,
    ) {}

    /**
     * @throws FileAvailabilityException
     * @throws FileChunkIntegrityException
     * @throws FileStorageAccessException
     */
    public function resolve(Request $request): ?UploadedFile
    {
        if ($request->request->has('dzuuid')) {
            return $this->uploadedFileBuilder->build($request);
        }

        return $request->files->get('file');
    }
}
