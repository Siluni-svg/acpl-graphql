<?php

namespace App\DataFixtures;

use App\Entity\GitProject;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class GitProjectFixtures extends Fixture
{
    public const REPOS = [
        'ArchipelagoMW/Archipelago',
        'symfony/symfony',
        'doctrine/orm',
        'facebook/react',
        'twbs/bootstrap',
        'laravel/laravel',
        'vuejs/core',
        'nodejs/node',
        'python/cpython',
        'rust-lang/rust',
    ];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        foreach (self::REPOS as $i => $repo) {
            $project = new GitProject()
                ->setUrl('https://github.com/' . $repo)
                ->setLastCheckedAt($faker->optional(0.7)->dateTimeBetween('-1 week'));

            $manager->persist($project);
            $this->addReference('git_project_' . $i, $project);
        }

        $manager->flush();
    }
}
