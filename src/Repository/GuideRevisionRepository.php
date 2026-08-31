<?php

namespace App\Repository;

use App\Entity\Guide;
use App\Entity\GuideRevision;
use App\Entity\User;
use App\Enum\StatusEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<GuideRevision> */
class GuideRevisionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GuideRevision::class);
    }

    public function findDraftForUser(Guide $guide, User $user): ?GuideRevision
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.guide = :guide')
            ->andWhere('r.created_by = :user')
            ->andWhere('r.status = :status')
            ->setParameter('guide', $guide)
            ->setParameter('user', $user)
            ->setParameter('status', StatusEnum::DRAFT)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /** @return GuideRevision[] */
    public function findDraftsForUser(User $user): array
    {
        return $this->createQueryBuilder('r')
            ->addSelect('g')
            ->innerJoin('r.guide', 'g')
            ->andWhere('r.created_by = :user')
            ->andWhere('r.status = :status')
            ->setParameter('user', $user)
            ->setParameter('status', StatusEnum::DRAFT)
            ->orderBy('r.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /** @return GuideRevision[] */
    public function findPendingReview(): array
    {
        return $this->createQueryBuilder('r')
            ->addSelect('g', 'createdBy')
            ->innerJoin('r.guide', 'g')
            ->innerJoin('r.created_by', 'createdBy')
            ->andWhere('r.status = :status')
            ->setParameter('status', StatusEnum::IN_REVIEW)
            ->orderBy('g.id', 'ASC')
            ->addOrderBy('r.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
