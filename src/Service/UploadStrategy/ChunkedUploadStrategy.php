<?php

namespace App\Service\UploadStrategy;

use App\Model\UploadRequest\ChunkedUploadRequest;
use App\Model\UploadRequest\UploadRequestInterface;
use App\Service\UploadedFileBuilder;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[AsTaggedItem(priority: 100)]
class ChunkedUploadStrategy implements UploadStrategyInterface
{
    public function __construct(
        protected UploadedFileBuilder $uploadedFileBuilder,
    ) {}

    public function supports(UploadRequestInterface $request): bool
    {
        return $request instanceof ChunkedUploadRequest;
    }

    /** @param ChunkedUploadRequest $request */
    public function extract(UploadRequestInterface $request): ?UploadedFile
    {
        return $this->uploadedFileBuilder->build($request);
    }
}
