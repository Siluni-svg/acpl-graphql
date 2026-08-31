<?php

namespace App\GraphQL\Type;

use App\Entity\User;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Type]
class UserConnection
{
    /** @var list<User> */
    #[GQL\Field(type: '[User]!')]
    public array $items;

    #[GQL\Field(type: 'Int!')]
    public int $totalCount;

    #[GQL\Field(type: 'PaginationInfo!')]
    public PaginationInfo $pageInfo;

    /** @param list<User> $items */
    public function __construct(array $items, int $totalCount, PaginationInfo $pageInfo)
    {
        $this->items = $items;
        $this->totalCount = $totalCount;
        $this->pageInfo = $pageInfo;
    }
}
