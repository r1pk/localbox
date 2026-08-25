<?php

namespace App\Service\UploadStrategy;

use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

#[AsTaggedItem(priority: 0)]
class DirectUploadStrategy implements UploadStrategyInterface
{
    public function supports(Request $request): bool
    {
        return $request->files->has('file');
    }

    public function extract(Request $request): ?UploadedFile
    {
        return $request->files->get('file');
    }
}
