<?php

namespace App\GraphQL\Type;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Type]
class PaginationInfo
{
    #[GQL\Field(type: 'Boolean!')]
    public bool $hasNextPage;

    #[GQL\Field(type: 'Boolean!')]
    public bool $hasPreviousPage;

    /** Value to pass as `pagination.after` to fetch the next page. */
    #[GQL\Field(type: 'String')]
    public ?string $nextCursor;

    /** Value to pass as `pagination.after` to fetch the previous page. */
    #[GQL\Field(type: 'String')]
    public ?string $previousCursor;

    public function __construct(bool $hasNextPage, bool $hasPreviousPage, ?string $nextCursor, ?string $previousCursor)
    {
        $this->hasNextPage = $hasNextPage;
        $this->hasPreviousPage = $hasPreviousPage;
        $this->nextCursor = $nextCursor;
        $this->previousCursor = $previousCursor;
    }
}
