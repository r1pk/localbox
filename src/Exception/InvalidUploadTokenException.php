<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class InvalidUploadTokenException extends ClientException
{
    const int RESPONSE_STATUS = Response::HTTP_FORBIDDEN;
}
