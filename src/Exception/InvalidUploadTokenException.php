<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class InvalidUploadTokenException extends ClientException
{
    public const int RESPONSE_STATUS = Response::HTTP_FORBIDDEN;
}
