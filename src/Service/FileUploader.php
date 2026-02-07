<?php

namespace App\Service;

use App\Exception\FileAvailabilityException;
use App\Exception\FileChunkIntegrityException;
use App\Exception\FileChunkValidationException;
use App\Exception\FileStorageAccessException;
use App\Exception\UploadTokenValidationException;
use App\Model\FileUploadResult;
use Symfony\Component\HttpFoundation\Request;

class FileUploader
{
    public function __construct(
        protected FileIngestor $ingestor,
        protected FilePersister $persister,
        protected GroupTokenRegistry $registry,
    ) {}

    /**
     * @throws FileAvailabilityException
     * @throws FileStorageAccessException
     * @throws FileChunkIntegrityException
     * @throws FileChunkValidationException
     * @throws UploadTokenValidationException
     */
    public function upload(Request $request): FileUploadResult
    {
        $token = $request->request->get('group_token', '');

        if (!$this->registry->isTokenValid($token)) {
            throw new UploadTokenValidationException('Group token is invalid or expired');
        }

        $file = $this->ingestor->handle($request);
        $entity = null;

        if ($file !== null) {
            $entity = $this->persister->persist($file, $token);
        }

        return new FileUploadResult($entity !== null, $token);
    }
}
