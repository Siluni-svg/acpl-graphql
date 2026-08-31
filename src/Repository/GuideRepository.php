<?php

namespace App\Repository;

use App\Entity\Guide;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<Guide> */
class GuideRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Guide::class);
    }

    /**
     * @return list<Guide>
     */
    public function findPublished(?string $search, string $language): array
    {
        $qb = $this->createQueryBuilder('g')
            ->addSelect('r')
            ->innerJoin('g.published_revision', 'r')
            ->andWhere('g.language = :language')
            ->setParameter('language', $language)
            ->orderBy('g.title', 'ASC');

        if (null !== $search && '' !== $search) {
            $qb->andWhere('LOWER(g.title) LIKE LOWER(:search) OR LOWER(r.content) LIKE LOWER(:search)')
                ->setParameter('search', '%'.$search.'%');
        }

        return $qb->getQuery()->getResult();
    }
}
