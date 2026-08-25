<?php

namespace App\Service\UploadStrategy;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

#[AutoconfigureTag]
interface UploadStrategyInterface
{
    public function supports(Request $request): bool;

    public function extract(Request $request): ?UploadedFile;
}
