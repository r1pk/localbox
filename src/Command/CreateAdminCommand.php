<?php

declare(strict_types = 1);

namespace App\Command;

use App\Entity\User;
use App\Enum\UserRole;
use App\Repository\UserRepository;
use App\Service\UserPasswordHasher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:create_admin', description: 'Hello PhpStorm')]
class CreateAdminCommand extends Command
{
    const string DEFAULT_ADMIN_NAME = 'admin';

    const string DEFAULT_ADMIN_PASSWORD = 'admin';

    public function __construct(
        protected UserPasswordHasher $hasher,
        protected UserRepository $repository,
        protected EntityManagerInterface $manager,
    )
    {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if ($this->repository->getByName(self::DEFAULT_ADMIN_NAME) !== null) {
            $io->info(
                sprintf('User "%s" already exists in the database. Skipping.', self::DEFAULT_ADMIN_NAME),
            );

            return Command::SUCCESS;
        }

        try {
            $user = new User();

            $user->setName(self::DEFAULT_ADMIN_NAME);
            $user->setPlainPassword(self::DEFAULT_ADMIN_PASSWORD);
            $user->setRoles([
                UserRole::User->value,
                UserRole::Admin->value,
            ]);

            $this->hasher->hash($user);

            $this->manager->persist($user);
            $this->manager->flush();

            $io->success(
                sprintf('User "%s" created successfully', self::DEFAULT_ADMIN_NAME),
            );

            return Command::SUCCESS;
        } catch (\Exception $exception) {
            $io->error(
                'Error: ' . $exception->getMessage(),
            );

            return Command::FAILURE;
        }
    }
}
