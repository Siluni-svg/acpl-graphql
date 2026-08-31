<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const LOCALES = ['fr', 'en'];

    public function load(ObjectManager $manager): void
    {
        $users = [];

        for ($a = 0; $a < 10; $a++) {
            $roles = $a == 0 ? ["ROLE_ADMIN", "ROLE_USER"] : ["ROLE_USER"];
            $user = new User()
                ->setUuid(sprintf('00000000-0000-0000-0000-00000000%04d', $a))
                ->setRoles($roles)
                ->setDiscordId(sprintf('discord-user-%d', $a))
                ->setDiscordUsername(sprintf('user%d', $a))
                ->setDiscordEmail(sprintf('user%d@example.test', $a))
                ->setDiscordAvatar('')
                ->setLocale(self::LOCALES[$a % count(self::LOCALES)])
                ->setEnabled(true)
                ->setLastConnectedAt(new \DateTime());

            $users[$a] = $user;
            $manager->persist($user);
            $this->addReference('user_' . $a, $user);
        }

        // exercise the self-referencing ban relation
        $users[9]
            ->setBannedAt(new \DateTimeImmutable('-1 week'))
            ->setBannedBy($users[0])
            ->setBannedReason('Spam');

        $manager->flush();
    }
}
