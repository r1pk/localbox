<?php

namespace App\Service\UploadStrategy;

use App\Model\UploadRequest\UploadRequestInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[AutoconfigureTag]
interface UploadStrategyInterface
{
    public function supports(UploadRequestInterface $request): bool;

    public function extract(UploadRequestInterface $request): ?UploadedFile;
}
