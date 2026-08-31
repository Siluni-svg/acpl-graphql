<?php

namespace App\DataFixtures;

use App\Entity\StreamAccount;
use App\Entity\User;
use App\Enum\StreamPlatformEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class StreamAccountFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($u = 0; $u < 6; $u++) {
            $user = $this->getReference('user_' . $u, User::class);

            $platforms = $faker->randomElements(StreamPlatformEnum::cases(), $faker->numberBetween(1, 2));

            foreach ($platforms as $platform) {
                $isLive = $faker->boolean(30);

                $streamAccount = new StreamAccount()
                    ->setUser($user)
                    ->setPlatform($platform)
                    ->setHandle($faker->userName())
                    ->setIsLive($isLive)
                    ->setIsArchipelagoTagged($faker->boolean(50))
                    ->setStreamUrl($faker->url())
                    ->setSyncedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 week')));

                if ($isLive) {
                    $streamAccount
                        ->setTitle($faker->sentence(6))
                        ->setThumbnail($faker->imageUrl(320, 180))
                        ->setViewerCount($faker->numberBetween(0, 5000));
                }

                $manager->persist($streamAccount);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
