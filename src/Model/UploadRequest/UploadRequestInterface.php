<?php

namespace App\Model\UploadRequest;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface UploadRequestInterface
{
    public function getGroupToken(): string;

    public function getPayload(): UploadedFile;
}
