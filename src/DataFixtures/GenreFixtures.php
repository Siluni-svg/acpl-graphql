<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GenreFixtures extends Fixture
{
    public const NAMES = [
        'Action', 'Adventure', 'RPG', 'Strategy', 'Simulation', 'Puzzle', 'Platformer', 'Shooter',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::NAMES as $i => $name) {
            $genre = new Genre()
                ->setIgdbId(1000 + $i)
                ->setName($name);

            $manager->persist($genre);
            $this->addReference('genre_' . $i, $genre);
        }

        $manager->flush();
    }
}
