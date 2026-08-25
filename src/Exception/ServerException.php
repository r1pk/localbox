<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

abstract class ServerException extends ApplicationException
{
    const int RESPONSE_STATUS = Response::HTTP_INTERNAL_SERVER_ERROR;

    public function getResponsePayload(): array
    {
        return ['error' => 'Internal server error'];
    }
}
