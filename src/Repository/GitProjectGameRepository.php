<?php

namespace App\Repository;

use App\Entity\GitProjectGame;
use App\Entity\User;
use App\Enum\StatusEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<GitProjectGame> */
class GitProjectGameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GitProjectGame::class);
    }

    /**
     * @return list<GitProjectGame>
     */
    public function findAllOrderedByGameName(?string $search = null, ?int $genreId = null, ?int $themeId = null): array
    {
        $qb = $this->createQueryBuilder('gpg')
            ->join('gpg.game', 'g')
            ->andWhere('gpg.status = :status')
            ->setParameter('status', StatusEnum::PUBLISHED)
            ->orderBy('g.name', 'ASC')
            ->addOrderBy('gpg.id', 'ASC');

        if (null !== $search && '' !== $search) {
            $qb->andWhere('LOWER(g.name) LIKE LOWER(:search)')
                ->setParameter('search', '%'.$search.'%');
        }

        if (null !== $genreId) {
            $qb->join('g.genres', 'genre')
                ->andWhere('genre.id = :genreId')
                ->setParameter('genreId', $genreId);
        }

        if (null !== $themeId) {
            $qb->join('g.themes', 'theme')
                ->andWhere('theme.id = :themeId')
                ->setParameter('themeId', $themeId);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @return list<GitProjectGame>
     */
    public function findPendingReview(): array
    {
        return $this->createQueryBuilder('gpg')
            ->addSelect('g', 'p', 'u')
            ->innerJoin('gpg.game', 'g')
            ->innerJoin('gpg.git_project', 'p')
            ->leftJoin('gpg.created_by', 'u')
            ->andWhere('gpg.status = :status')
            ->setParameter('status', StatusEnum::IN_REVIEW)
            ->orderBy('gpg.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return list<int>
     */
    public function findWatchedIdsForUser(User $user): array
    {
        $rows = $this->createQueryBuilder('gpg')
            ->select('gpg.id')
            ->innerJoin('gpg.watchedByUsers', 'u')
            ->andWhere('u = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getScalarResult();

        return array_map(static fn (array $row) => (int) $row['id'], $rows);
    }
}
