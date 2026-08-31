<?php

namespace App\GraphQL\Input;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Input(name: 'PaginationInput')]
class PaginationInput
{
    /** Number of items to take starting at (or after) `cursor`. Ignored if `last` is set. */
    #[GQL\InputField(type: 'Int', name: 'first')]
    public ?int $first = null;

    /** Number of items to take ending right before `cursor`. Takes over `first` when set. */
    #[GQL\InputField(type: 'Int', name: 'last')]
    public ?int $last = null;

    /**
     * Position to paginate from — combine with `first` to move forward from it,
     * or with `last` to move backward up to it. Use the `nextCursor`/`previousCursor`
     * values from a previous response's `pageInfo` rather than computing it by hand.
     */
    #[GQL\InputField(type: 'String', name: 'cursor')]
    public ?string $cursor = null;
}
