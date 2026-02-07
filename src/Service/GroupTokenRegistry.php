<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Uid\Uuid;

class GroupTokenRegistry
{
    const string SESSION_TOKEN_STORAGE_KEY = 'group_tokens';

    const int MAXIMUM_TOKENS_PER_SESSION = 128;

    public function __construct(protected RequestStack $stack) {}

    public function issueToken(): string
    {
        $session = $this->stack->getSession();
        $token = Uuid::v4()->toRfc4122();

        $tokens = $session->get(self::SESSION_TOKEN_STORAGE_KEY, []);
        $tokens[] = $token;

        if (count($tokens) > self::MAXIMUM_TOKENS_PER_SESSION) {
            array_shift($tokens);
        }

        $session->set(self::SESSION_TOKEN_STORAGE_KEY, $tokens);

        return $token;
    }

    public function isTokenValid(string $token): bool
    {
        $session = $this->stack->getSession();
        $tokens = $session->get(self::SESSION_TOKEN_STORAGE_KEY, []);

        return in_array($token, $tokens);
    }
}
