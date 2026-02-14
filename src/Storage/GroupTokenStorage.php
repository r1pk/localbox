<?php

namespace App\Storage;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class GroupTokenStorage
{
    const string SESSION_KEY = 'group-tokens';

    const int MAXIMUM_TOKENS = 128;

    public function __construct(
        protected RequestStack $stack,
    ) {}

    public function add(string $token): string
    {
        $tokens = $this->all();
        $tokens[] = $token;

        if (count($tokens) > self::MAXIMUM_TOKENS) {
            array_shift($tokens);
        }

        $this->getSession()->set(self::SESSION_KEY, $tokens);

        return $token;
    }

    public function contains(string $token): bool
    {
        return in_array($token, $this->all(), true);
    }

    public function all(): array
    {
        return $this->getSession()->get(self::SESSION_KEY, []);
    }

    private function getSession(): SessionInterface
    {
        return $this->stack->getSession();
    }
}
