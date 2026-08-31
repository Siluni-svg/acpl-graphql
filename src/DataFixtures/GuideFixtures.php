<?php

namespace App\DataFixtures;

use App\Entity\Guide;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GuideFixtures extends Fixture
{
    public const LOCALES = ['fr', 'en'];

    public const TITLES = [
        'Getting Started', 'Advanced Settings', 'Troubleshooting', 'Multiworld Basics', 'YAML Configuration',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::TITLES as $i => $title) {
            foreach (self::LOCALES as $locale) {
                $guide = new Guide()
                    ->setTitle($title)
                    ->setSlug($this->slugify($title) . '-' . $locale)
                    ->setLanguage($locale);

                $manager->persist($guide);
                $this->addReference('guide_' . $i . '_' . $locale, $guide);
            }
        }

        $manager->flush();
    }

    private function slugify(string $value): string
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9]+/', '-', $value), '-'));
    }
}
