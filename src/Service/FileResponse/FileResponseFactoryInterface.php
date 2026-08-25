<?php

namespace App\Service\FileResponse;

use App\Model\FileLocation\FileLocationInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\HttpFoundation\Response;

#[AutoconfigureTag]
interface FileResponseFactoryInterface
{
    public function supports(FileLocationInterface $location): bool;

    public function create(FileLocationInterface $location, string $filename): Response;
}
