<?php

namespace App\DataFixtures;

use App\Entity\GitProjectGame;
use App\Entity\User;
use App\Enum\StatusEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class GitProjectGameFixtures extends Fixture implements DependentFixtureInterface
{
    public const COUNT = 10;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < self::COUNT; $i++) {
            $status = $faker->randomElement(StatusEnum::cases());

            $gitProjectGame = new GitProjectGame()
                ->setGitProject($this->getReference('git_project_' . ($i % count(GitProjectFixtures::REPOS)), \App\Entity\GitProject::class))
                ->setGame($this->getReference('game_' . ($i % count(GameFixtures::NAMES)), \App\Entity\Game::class))
                ->setSlug($faker->unique()->slug(3))
                ->setTagRegex($faker->optional(0.4)->regexify('v?\d+\.\d+\.\d+'))
                ->setStatus($status)
                ->setCreatedBy($this->getReference('user_' . $faker->numberBetween(0, 9), User::class));

            if (in_array($status, [StatusEnum::PUBLISHED, StatusEnum::REJECTED], true)) {
                $gitProjectGame
                    ->setValidatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-6 months')))
                    ->setValidatedBy($this->getReference('user_0', User::class));
            }

            $manager->persist($gitProjectGame);
            $this->addReference('git_project_game_' . $i, $gitProjectGame);

            foreach ($faker->randomElements(range(0, 9), $faker->numberBetween(0, 3)) as $userIndex) {
                $this->getReference('user_' . $userIndex, User::class)->addWatchedGitProjectGame($gitProjectGame);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [GitProjectFixtures::class, GameFixtures::class, UserFixtures::class];
    }
}
