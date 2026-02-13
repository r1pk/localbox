<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserPasswordHasher
{
    public function __construct(
        protected UserPasswordHasherInterface $hasher,
    ) {}

    public function hash(User $user): void
    {
        if ($user->getPlainPassword() === null) {
            return;
        }

        $hash = $this->hasher->hashPassword(
            $user,
            $user->getPlainPassword(),
        );

        $user->setPassword($hash);
        $user->eraseCredentials();
    }
}
