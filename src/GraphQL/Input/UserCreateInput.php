<?php

namespace App\GraphQL\Input;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Input(name: 'UserCreateInput')]
class UserCreateInput
{
    #[GQL\InputField(type: 'String!', name: 'uuid')]
    public ?string $uuid = null;

    #[GQL\InputField(type: 'String!', name: 'discordId')]
    public ?string $discordId = null;

    #[GQL\InputField(type: 'String!', name: 'discordUsername')]
    public ?string $discordUsername = null;

    #[GQL\InputField(type: 'String!', name: 'discordEmail')]
    public ?string $discordEmail = null;

    #[GQL\InputField(type: 'String!', name: 'discordAvatar')]
    public ?string $discordAvatar = null;

    /** Falls back to the resolved request locale (see LocaleResolver) when omitted. */
    #[GQL\InputField(type: 'String', name: 'locale')]
    public ?string $locale = null;
}
