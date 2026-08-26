<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class FileNotFoundException extends ClientException
{
    public const int RESPONSE_STATUS = Response::HTTP_NOT_FOUND;
}
