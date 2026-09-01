<?php

namespace App\GraphQL\Mutation;

use App\Entity\User;
use App\GraphQL\Input\UserCreateInput;
use App\GraphQL\Input\UserUpdateInput;
use App\GraphQL\LocaleResolver;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Annotation as GQL;
use Overblog\GraphQLBundle\Error\UserError;

#[GQL\Provider]
class UserMutations
{
    public function __construct(
        private readonly UserRepository $repository,
        private readonly EntityManagerInterface $entityManager,
        private readonly LocaleResolver $localeResolver,
    ) {}

    #[GQL\Mutation(type: 'User!', name: 'addUser')]
    public function addUser(UserCreateInput $input): User
    {
        $user = new User()
            ->setUuid($input->uuid)
            ->setRoles(['ROLE_USER'])
            ->setDiscordId($input->discordId)
            ->setDiscordUsername($input->discordUsername)
            ->setDiscordEmail($input->discordEmail)
            ->setDiscordAvatar($input->discordAvatar)
            ->setLocale($input->locale ?? $this->localeResolver->resolve())
            ->setEnabled(true)
            ->setLastConnectedAt(new \DateTime());

        $this->entityManager->persist($user);

        try {
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException) {
            throw new UserError('A user with this identity already exists.');
        }

        return $user;
    }

    #[GQL\Mutation(type: 'User!', name: 'updateUser')]
    #[GQL\Access("isAuthenticated() and (getUser().getUserIdentifier() == args['uuid'] or hasRole('ROLE_ADMIN'))")]
    public function updateUser(string $uuid, UserUpdateInput $input): User
    {
        $user = $this->repository->findOneBy(['uuid' => $uuid]);

        if (!$user instanceof User) {
            throw new UserError('User not found.');
        }

        if (null !== $input->discordUsername) {
            $user->setDiscordUsername($input->discordUsername);
        }

        if (null !== $input->discordEmail) {
            $user->setDiscordEmail($input->discordEmail);
        }

        if (null !== $input->discordAvatar) {
            $user->setDiscordAvatar($input->discordAvatar);
        }

        if (null !== $input->locale) {
            $user->setLocale($input->locale);
        }

        $this->entityManager->flush();

        return $user;
    }
}
