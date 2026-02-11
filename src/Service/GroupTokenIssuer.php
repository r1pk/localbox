<?php

namespace App\Service;

use App\Storage\GroupTokenStorage;
use Symfony\Component\Uid\Uuid;

class GroupTokenIssuer
{
    public function __construct(
        protected GroupTokenStorage $groupTokenStorage,
    ) {}

    public function issue(): string
    {
        $token = Uuid::v4()->toRfc4122();

        $this->groupTokenStorage->add($token);

        return $token;
    }

    public function isValid(string $token): bool
    {
        return $this->groupTokenStorage->contains($token);
    }
}
