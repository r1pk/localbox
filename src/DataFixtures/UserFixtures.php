<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Enum\UserRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        protected UserPasswordHasherInterface $hasher,
    ) {}

    public function load(ObjectManager $manager): void
    {
        $user = new User();

        $name = 'admin';
        $hash = $this->hasher->hashPassword($user, $name);

        $user->setName($name);
        $user->setPassword($hash);
        $user->setRoles([
            UserRole::User->value,
            UserRole::Admin->value,
        ]);

        $manager->persist($user);
        $manager->flush();
    }
}
