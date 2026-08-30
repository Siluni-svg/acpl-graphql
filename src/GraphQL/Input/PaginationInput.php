<?php

namespace App\GraphQL\Input;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Input(name: 'PaginationInput')]
class PaginationInput
{
    #[GQL\InputField(type: 'Int', name: 'first', defaultValue: 20)]
    public int $first = 20;

    #[GQL\InputField(type: 'String', name: 'after')]
    public ?string $after = null;
}
