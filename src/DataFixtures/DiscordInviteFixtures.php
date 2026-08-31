<?php

namespace App\DataFixtures;

use App\Entity\DiscordInvite;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class DiscordInviteFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 5; $i++) {
            $syncedAt = $faker->optional(0.9)->dateTimeBetween('-1 month');

            $invite = new DiscordInvite()
                ->setUrl('https://discord.gg/' . $faker->lexify('???????'))
                ->setCode($faker->lexify('???????'))
                ->setGuildId($faker->numerify(str_repeat('#', 18)))
                ->setName($faker->company())
                ->setDescription($faker->optional(0.8)->sentence(12))
                ->setIcon($faker->optional(0.8)->imageUrl(128, 128))
                ->setTraits($faker->randomElements(['PARTNERED', 'VERIFIED', 'COMMUNITY'], $faker->numberBetween(0, 2)))
                ->setApproximateMemberCount($faker->optional(0.9)->numberBetween(50, 50000))
                ->setApproximatePresenceCount($faker->optional(0.9)->numberBetween(10, 10000))
                ->setSubmittedBy($this->getReference('user_' . $faker->numberBetween(0, 9), User::class))
                ->setSyncedAt(null !== $syncedAt ? \DateTimeImmutable::createFromMutable($syncedAt) : null);

            $manager->persist($invite);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
