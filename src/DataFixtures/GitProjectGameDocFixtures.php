<?php

namespace App\DataFixtures;

use App\Entity\GitProjectGameDoc;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class GitProjectGameDocFixtures extends Fixture implements DependentFixtureInterface
{
    public const LOCALES = ['fr', 'en'];

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < GitProjectGameFixtures::COUNT; $i++) {
            $gitProjectGame = $this->getReference('git_project_game_' . $i, \App\Entity\GitProjectGame::class);

            foreach (self::LOCALES as $locale) {
                $doc = new GitProjectGameDoc()
                    ->setGitProjectGame($gitProjectGame)
                    ->setLanguage($locale);

                $manager->persist($doc);
                $this->addReference('git_project_game_doc_' . $i . '_' . $locale, $doc);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [GitProjectGameFixtures::class];
    }
}
