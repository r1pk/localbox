<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

abstract class ClientException extends ApplicationException
{
    const int RESPONSE_STATUS = Response::HTTP_UNPROCESSABLE_ENTITY;
}
