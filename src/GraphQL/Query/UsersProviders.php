<?php

namespace App\GraphQL\Query;

use App\GraphQL\Input\PaginationInput;
use App\GraphQL\Input\UserFilter;
use App\Repository\UserRepository;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Provider]
class UsersProviders
{
    public function __construct(
        private readonly UserRepository $repository,
    ) {}

    #[GQL\Query(type: "[User]", name: "users")]
    public function getUsers(?UserFilter $filter = null, ?PaginationInput $pagination = null)
    {
        return $this->repository->findByFilterPagination($filter, $pagination);
    }
}
