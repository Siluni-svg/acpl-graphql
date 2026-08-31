<?php

namespace App\DataFixtures;

use App\Entity\Theme;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ThemeFixtures extends Fixture
{
    public const NAMES = [
        'Fantasy', 'Sci-Fi', 'Horror', 'Historical', 'Survival', 'Comedy', 'Mystery', 'Post-apocalyptic',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::NAMES as $i => $name) {
            $theme = new Theme()
                ->setIgdbId(2000 + $i)
                ->setName($name);

            $manager->persist($theme);
            $this->addReference('theme_' . $i, $theme);
        }

        $manager->flush();
    }
}
