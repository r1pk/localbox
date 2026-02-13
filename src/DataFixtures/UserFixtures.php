<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Enum\UserRole;
use App\Service\UserPasswordHasher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function __construct(
        protected UserPasswordHasher $hasher,
    ) {}

    public function load(ObjectManager $manager): void
    {
        $user = new User();

        $user->setName('admin');
        $user->setPlainPassword('admin');
        $user->setRoles([
            UserRole::User->value,
            UserRole::Admin->value,
        ]);

        $this->hasher->hash($user);

        $manager->persist($user);
        $manager->flush();
    }
}
