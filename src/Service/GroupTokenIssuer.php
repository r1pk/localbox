<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class GroupTokenIssuer
{
    protected const string SESSION_KEY = 'group-tokens';

    protected const int MAXIMUM_TOKENS = 128;

    public function __construct(
        protected TokenGenerator $tokenGenerator,
        protected RequestStack $requestStack,
    ) {}

    public function issue(): string
    {
        $token = $this->tokenGenerator->generate();
        $this->storeToken($token);

        return $token;
    }

    public function isValid(string $token): bool
    {
        return in_array($token, $this->getStoredTokens(), true);
    }

    protected function storeToken(string $token): void
    {
        $tokens = $this->getStoredTokens();
        $tokens[] = $token;

        if (count($tokens) > self::MAXIMUM_TOKENS) {
            array_shift($tokens);
        }

        $this->getSession()->set(self::SESSION_KEY, $tokens);
    }

    protected function getStoredTokens(): array
    {
        return $this->getSession()->get(self::SESSION_KEY, []);
    }

    protected function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }
}
