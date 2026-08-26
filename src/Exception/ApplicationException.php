<?php

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

abstract class ApplicationException extends Exception
{
    public const int RESPONSE_STATUS = Response::HTTP_INTERNAL_SERVER_ERROR;

    /** @return array<string, string> */
    public function getResponsePayload(): array
    {
        return [
            'error' => $this->getMessage(),
        ];
    }

    public function getResponseStatus(): int
    {
        return static::RESPONSE_STATUS;
    }
}
