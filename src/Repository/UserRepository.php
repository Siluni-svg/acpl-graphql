<?php

namespace App\Repository;

use App\Entity\User;
use App\GraphQL\Input\PaginationInput;
use App\GraphQL\Input\UserFilter;
use App\GraphQL\Type\PaginationInfo;
use App\GraphQL\Type\UserConnection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<User> */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findByFilterPagination(?UserFilter $filter, ?PaginationInput $pagination): UserConnection
    {
        $totalCount = (int) $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $cursor = null !== $pagination?->cursor ? (int) $pagination->cursor : null;
        $first = $pagination?->first;
        $last = $pagination?->last;

        if (null !== $first && null !== $last) {
            // Combined: slice `first` items from the cursor, then keep only the last `last` of that slice.
            $windowStart = $cursor ?? 0;
            $windowSize = min($first, max(0, $totalCount - $windowStart));
            $limit = min($last, $windowSize);
            $offset = $windowStart + $windowSize - $limit;
        } elseif (null !== $last) {
            // Backward: take `last` items ending right before the cursor (defaults to the end of the list).
            $end = $cursor ?? $totalCount;
            $offset = max(0, $end - $last);
            $limit = $end - $offset;
        } else {
            // Forward (default): take `first` items starting at the cursor (defaults to the start of the list).
            $offset = $cursor ?? 0;
            $limit = $first ?? 20;
        }

        $items = $this->createQueryBuilder('u')
            ->orderBy('u.id', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->execute()
        ;

        $end = $offset + \count($items);
        $hasNextPage = $end < $totalCount;
        $hasPreviousPage = $offset > 0;

        return new UserConnection(
            items: $items,
            totalCount: $totalCount,
            pageInfo: new PaginationInfo(
                hasNextPage: $hasNextPage,
                hasPreviousPage: $hasPreviousPage,
                // Reuse with `first` to continue forward, or with `last` to continue backward.
                nextCursor: $hasNextPage ? (string) $end : null,
                previousCursor: $hasPreviousPage ? (string) $offset : null,
            ),
        );
    }
}
