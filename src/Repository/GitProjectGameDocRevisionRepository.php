<?php

namespace App\Repository;

use App\Entity\GitProjectGameDoc;
use App\Entity\GitProjectGameDocRevision;
use App\Entity\User;
use App\Enum\StatusEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<GitProjectGameDocRevision> */
class GitProjectGameDocRevisionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GitProjectGameDocRevision::class);
    }

    public function findDraftForUser(GitProjectGameDoc $doc, User $user): ?GitProjectGameDocRevision
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.git_project_game_doc = :doc')
            ->andWhere('r.created_by = :user')
            ->andWhere('r.status = :status')
            ->setParameter('doc', $doc)
            ->setParameter('user', $user)
            ->setParameter('status', StatusEnum::DRAFT)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /** @return GitProjectGameDocRevision[] */
    public function findLatestPublished(int $limit = 5): array
    {
        return $this->createQueryBuilder('r')
            ->addSelect('d', 'g')
            ->innerJoin('r.git_project_game_doc', 'd')
            ->innerJoin('d.git_project_game', 'g')
            ->where('d.published_revision = r.id')
            ->andWhere('g.status = :status')
            ->setParameter('status', StatusEnum::PUBLISHED)
            ->orderBy('r.validated_at', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /** @return GitProjectGameDocRevision[] */
    public function findDraftsForUser(User $user): array
    {
        return $this->createQueryBuilder('r')
            ->addSelect('d', 'g', 'gg')
            ->innerJoin('r.git_project_game_doc', 'd')
            ->innerJoin('d.git_project_game', 'g')
            ->innerJoin('g.game', 'gg')
            ->andWhere('r.created_by = :user')
            ->andWhere('r.status = :status')
            ->setParameter('user', $user)
            ->setParameter('status', StatusEnum::DRAFT)
            ->orderBy('r.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /** @return GitProjectGameDocRevision[] */
    public function findPendingReview(): array
    {
        return $this->createQueryBuilder('r')
            ->addSelect('d', 'g', 'gg', 'createdBy')
            ->innerJoin('r.git_project_game_doc', 'd')
            ->innerJoin('d.git_project_game', 'g')
            ->innerJoin('g.game', 'gg')
            ->innerJoin('r.created_by', 'createdBy')
            ->andWhere('r.status = :status')
            ->setParameter('status', StatusEnum::IN_REVIEW)
            ->orderBy('d.id', 'ASC')
            ->addOrderBy('r.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
