<?php

namespace App\GraphQL\Query;

use App\GraphQL\Input\PaginationInput;
use App\GraphQL\Input\UserFilter;
use App\GraphQL\Type\UserConnection;
use App\Repository\UserRepository;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Provider]
class UsersProviders
{
    public function __construct(
        private readonly UserRepository $repository,
    ) {}

    #[GQL\Query(type: "UserConnection!", name: "users")]
    public function getUsers(?UserFilter $filter = null, ?PaginationInput $pagination = null): UserConnection
    {
        return $this->repository->findByFilterPagination($filter, $pagination);
    }
}
