<?php

namespace App\DataFixtures;

use App\Entity\GuideRevision;
use App\Entity\User;
use App\Enum\StatusEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class GuideRevisionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $fakers = [
            'fr' => Factory::create('fr_FR'),
            'en' => Factory::create('en_US'),
        ];

        foreach (array_keys(GuideFixtures::TITLES) as $i) {
            foreach (GuideFixtures::LOCALES as $locale) {
                $faker = $fakers[$locale];
                $guide = $this->getReference('guide_' . $i . '_' . $locale, \App\Entity\Guide::class);

                $revisionCount = $faker->numberBetween(1, 2);
                $publishedRevision = null;

                for ($r = 0; $r < $revisionCount; $r++) {
                    $isLast = $r === $revisionCount - 1;
                    $status = $isLast ? StatusEnum::PUBLISHED : $faker->randomElement(StatusEnum::cases());

                    $revision = new GuideRevision()
                        ->setGuide($guide)
                        ->setContent($faker->paragraphs($faker->numberBetween(3, 8), true))
                        ->setStatus($status)
                        ->setCreatedBy($this->getReference('user_' . $faker->numberBetween(0, 9), User::class));

                    if (StatusEnum::DRAFT !== $status) {
                        $revision
                            ->setValidatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-3 months')))
                            ->setValidatedBy($this->getReference('user_0', User::class));
                    }

                    $manager->persist($revision);

                    if ($isLast) {
                        $publishedRevision = $revision;
                    }
                }

                $guide->setPublishedRevision($publishedRevision);
                $manager->persist($guide);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [GuideFixtures::class, UserFixtures::class];
    }
}
