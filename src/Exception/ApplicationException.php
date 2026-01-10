<?php

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

abstract class ApplicationException extends Exception
{
    const int RESPONSE_STATUS = Response::HTTP_INTERNAL_SERVER_ERROR;

    public function getResponsePayload(): array
    {
        return [
            'error' => $this->getMessage(),
        ];
    }

    public function getResponseStatus(): int
    {
        return self::RESPONSE_STATUS;
    }
}
