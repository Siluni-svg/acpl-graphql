<?php

namespace App\GraphQL\Input;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Input(name: 'UserUpdateInput')]
class UserUpdateInput
{
    #[GQL\InputField(type: 'String', name: 'discordUsername')]
    public ?string $discordUsername = null;

    #[GQL\InputField(type: 'String', name: 'discordEmail')]
    public ?string $discordEmail = null;

    #[GQL\InputField(type: 'String', name: 'discordAvatar')]
    public ?string $discordAvatar = null;

    #[GQL\InputField(type: 'String', name: 'locale')]
    public ?string $locale = null;
}
