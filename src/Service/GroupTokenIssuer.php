<?php

namespace App\Service;

use App\Storage\GroupTokenStorage;

class GroupTokenIssuer
{
    public function __construct(
        protected TokenGenerator $tokenGenerator,
        protected GroupTokenStorage $groupTokenStorage,
    ) {}

    public function issue(): string
    {
        return $this->groupTokenStorage->add(
            $this->tokenGenerator->generate(),
        );
    }

    public function isValid(string $token): bool
    {
        return $this->groupTokenStorage->contains(
            $token,
        );
    }
}
