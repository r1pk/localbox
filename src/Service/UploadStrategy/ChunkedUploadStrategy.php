<?php

namespace App\Service\UploadStrategy;

use App\Service\UploadedFileBuilder;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

#[AsTaggedItem(priority: 100)]
class ChunkedUploadStrategy implements UploadStrategyInterface
{
    public function __construct(
        protected UploadedFileBuilder $uploadedFileBuilder,
    ) {}

    public function supports(Request $request): bool
    {
        return $request->request->has('dzuuid') && $request->files->has('file');
    }

    public function extract(Request $request): ?UploadedFile
    {
        return $this->uploadedFileBuilder->build($request);
    }
}
