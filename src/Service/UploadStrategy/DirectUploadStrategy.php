<?php

namespace App\Service\UploadStrategy;

use App\Model\UploadRequest\DirectUploadRequest;
use App\Model\UploadRequest\UploadRequestInterface;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[AsTaggedItem(priority: 0)]
class DirectUploadStrategy implements UploadStrategyInterface
{
    public function supports(UploadRequestInterface $request): bool
    {
        return $request instanceof DirectUploadRequest;
    }

    public function extract(UploadRequestInterface $request): ?UploadedFile
    {
        return $request->getPayload();
    }
}
