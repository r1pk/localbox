<?php

namespace App\Repository;

use App\Entity\File;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<File>
 */
class FileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, File::class);
    }

    public function findByToken(string $token): ?File
    {
        return $this->findOneBy(['token' => $token]);
    }

    public function findByGroupToken(string $token): array
    {
        return $this->findBy(['groupToken' => $token]);
    }

    public function getSummary(): array
    {
        $builder = $this->createQueryBuilder('f');

        $builder->select([
            'count(f.id) as total_count',
            'sum(f.size) as total_size',
            'sum(case when f.createdAt >= :today then 1 else 0 end) as today_count',
        ]);
        $builder->setParameter('today', new DateTime('today'));

        return $builder->getQuery()->getSingleResult();
    }
}
