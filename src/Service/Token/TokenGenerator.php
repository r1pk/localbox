<?php

namespace App\Service\Token;

use Symfony\Component\Uid\Uuid;

class TokenGenerator
{
    public function generate(): string
    {
        return Uuid::v7()->toBase58();
    }
}
