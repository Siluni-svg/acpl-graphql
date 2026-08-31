<?php

namespace App\Repository;

use App\Entity\GitProject;
use App\Enum\StatusEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<GitProject> */
class GitProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GitProject::class);
    }

    /** @return GitProject[] */
    public function findNextGithubToCheck(int $limit = 10, ?\DateTimeInterface $lastCheckedBefore = null): array
    {
        $lastCheckedBefore ??= new \DateTimeImmutable('-2 hours');

        return $this->createQueryBuilder('gp')
            ->andWhere('gp.url LIKE :url')->setParameter('url', '%github%')
            // ->andWhere('gp.status = :status')->setParameter(':status', StatusEnum::PUBLISHED)
            ->andWhere('gp.last_checked_at IS NULL OR gp.last_checked_at < :lastCheckedBefore')
            ->setParameter('lastCheckedBefore', $lastCheckedBefore)
            ->addOrderBy('CASE WHEN gp.last_checked_at IS NULL THEN 0 ELSE 1 END', 'ASC')
            ->addOrderBy('gp.last_checked_at', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
