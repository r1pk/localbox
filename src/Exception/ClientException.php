<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

abstract class ClientException extends ApplicationException
{
    public const int RESPONSE_STATUS = Response::HTTP_BAD_REQUEST;
}
