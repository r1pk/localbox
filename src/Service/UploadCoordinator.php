<?php

namespace App\Service;

use App\Exception\InvalidUploadTokenException;
use App\Exception\UnsupportedUploadRequestException;
use App\Model\FileUploadResult;
use App\Service\UploadStrategy\UploadStrategyResolver;
use Symfony\Component\HttpFoundation\Request;

class UploadCoordinator
{
    public function __construct(
        protected GroupTokenIssuer $groupTokenIssuer,
        protected UploadStrategyResolver $uploadStrategyResolver,
        protected UploadedFilePersister $uploadedFilePersister,
    ) {}

    /**
     * @throws UnsupportedUploadRequestException
     * @throws InvalidUploadTokenException
     */
    public function upload(Request $request): FileUploadResult
    {
        $token = (string) $request->request->get('group_token', '');

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
