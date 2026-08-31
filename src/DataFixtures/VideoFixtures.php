<?php

namespace App\DataFixtures;

use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class VideoFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 12; $i++) {
            $video = new Video()
                ->setPlatform($faker->randomElement(['youtube', 'twitch']))
                ->setPlatformVideoId($faker->regexify('[A-Za-z0-9_-]{11}'))
                ->setUrl($faker->url())
                ->setTitle($faker->optional(0.9)->sentence(6))
                ->setChannel($faker->optional(0.9)->userName())
                ->setThumbnail($faker->optional(0.8)->imageUrl(320, 180))
                ->setPublishedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-2 years')))
                ->setEnabled($faker->boolean(90));

            $manager->persist($video);
        }

        $manager->flush();
    }
}
