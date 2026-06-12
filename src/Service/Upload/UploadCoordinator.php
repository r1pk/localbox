<?php

namespace App\Service\Upload;

use App\Exception\FileAvailabilityException;
use App\Exception\FileChunkIntegrityException;
use App\Exception\FileStorageAccessException;
use App\Exception\UploadTokenValidationException;
use App\Model\FileUploadResult;
use App\Service\Token\GroupTokenIssuer;
use Symfony\Component\HttpFoundation\Request;

class UploadCoordinator
{
    public function __construct(
        protected UploadedFileResolver $uploadedFileResolver,
        protected UploadedFileRegistrar $uploadedFileRegistrar,
        protected GroupTokenIssuer $groupTokenIssuer,
    ) {}

    /**
     * @throws FileAvailabilityException
     * @throws FileStorageAccessException
     * @throws FileChunkIntegrityException
     * @throws UploadTokenValidationException
     */
    public function upload(Request $request): FileUploadResult
    {
        $token = $request->request->get('group_token', '');

        if (!$this->groupTokenIssuer->isValid($token)) {
            throw new UploadTokenValidationException('Group token is invalid or expired');
        }

        $file = $this->uploadedFileResolver->resolve($request);
        $entity = null;

        if ($file !== null) {
            $entity = $this->uploadedFileRegistrar->register($file, $token);
        }

        return new FileUploadResult($entity !== null, $token);
    }
}
