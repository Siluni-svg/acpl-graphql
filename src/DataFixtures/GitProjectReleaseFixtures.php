<?php

namespace App\DataFixtures;

use App\Entity\GitProjectRelease;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class GitProjectReleaseFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        foreach (array_keys(GitProjectFixtures::REPOS) as $i) {
            $gitProject = $this->getReference('git_project_' . $i, \App\Entity\GitProject::class);
            $releaseCount = $faker->numberBetween(1, 4);

            $major = $faker->numberBetween(0, 5);
            for ($r = 0; $r < $releaseCount; $r++) {
                $tag = sprintf('v%d.%d.%d', $major, $r, $faker->numberBetween(0, 9));

                $release = new GitProjectRelease()
                    ->setGitProject($gitProject)
                    ->setRelease($tag)
                    ->setTag($tag)
                    ->setPublishedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-2 years')));

                $manager->persist($release);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [GitProjectFixtures::class];
    }
}
