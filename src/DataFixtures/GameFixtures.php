<?php

namespace App\DataFixtures;

use App\Entity\Game;
use App\Entity\Genre;
use App\Entity\Theme;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class GameFixtures extends Fixture implements DependentFixtureInterface
{
    public const NAMES = [
        'Ocarina of Time', 'Hollow Knight', 'Celeste', 'Stardew Valley', 'Terraria',
        'Super Metroid', 'Dark Souls', 'Minecraft', 'Hades', 'Undertale',
        'Risk of Rain 2', 'A Link to the Past', "Banjo-Kazooie", 'Rogue Legacy', 'Timespinner',
    ];

    private const PEGIS = [3, 7, 12, 16, 18];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        foreach (self::NAMES as $i => $name) {
            $igdbId = $faker->optional(0.9)->numberBetween(1000, 999999);

            $game = new Game()
                ->setName($name)
                ->setPegi($faker->optional(0.8)->randomElement(self::PEGIS))
                ->setIgdbId($igdbId)
                ->setCoverImage($faker->imageUrl(264, 352))
                ->setSynopsis($faker->paragraphs(3, true))
                ->setRestricted($faker->boolean(15));

            foreach ($faker->randomElements(range(0, count(GenreFixtures::NAMES) - 1), $faker->numberBetween(1, 3)) as $genreIndex) {
                $game->addGenre($this->getReference('genre_' . $genreIndex, Genre::class));
            }

            foreach ($faker->randomElements(range(0, count(ThemeFixtures::NAMES) - 1), $faker->numberBetween(1, 3)) as $themeIndex) {
                $game->addTheme($this->getReference('theme_' . $themeIndex, Theme::class));
            }

            $manager->persist($game);
            $this->addReference('game_' . $i, $game);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [GenreFixtures::class, ThemeFixtures::class];
    }
}
