<?php

namespace App\Repository;

use App\Entity\User;
use App\GraphQL\Input\PaginationInput;
use App\GraphQL\Input\UserFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<User> */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findByFilterPagination(?UserFilter $filter, ?PaginationInput $pagination): array
    {
        return $this->createQueryBuilder('u')
            //->setFirstResult($pagination?->after ?? 0)
            //->setMaxResults($pagination?->first ?? 20)
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->execute()
        ;
    }
}
