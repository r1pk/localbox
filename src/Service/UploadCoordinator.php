<?php

namespace App\Service;

use App\Exception\InvalidUploadTokenException;
use App\Exception\MissingUploadStrategyException;
use App\Model\FileUploadResult;
use App\Model\UploadRequest\UploadRequestInterface;
use App\Service\UploadStrategy\UploadStrategyResolver;

class UploadCoordinator
{
    public function __construct(
        protected GroupTokenIssuer $groupTokenIssuer,
        protected UploadStrategyResolver $uploadStrategyResolver,
        protected UploadedFilePersister $uploadedFilePersister,
    ) {}

    /**
     * @throws InvalidUploadTokenException
     * @throws MissingUploadStrategyException
     */
    public function upload(UploadRequestInterface $request): FileUploadResult
    {
        $token = $request->getGroupToken();

        if (!$this->groupTokenIssuer->isValid($token)) {
            throw new InvalidUploadTokenException('Upload group token is invalid or has expired');
        }

        $strategy = $this->uploadStrategyResolver->resolve($request);
        $file = $strategy->extract($request);

        if ($file === null) {
            return new FileUploadResult(false, $token);
        }

        $this->uploadedFilePersister->persist($file, $token);

        return new FileUploadResult(true, $token);
    }
}
