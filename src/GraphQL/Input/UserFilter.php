<?php

namespace App\GraphQL\Input;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Input(name: 'UserFilter')]
class UserFilter
{
    #[GQL\InputField(type: 'String', name: 'uuid')]
    public ?string $uuid = null;

    #[GQL\InputField(type: '[String]', name: 'roles')]
    public ?array $roles = null;
}
