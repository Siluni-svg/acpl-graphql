<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($a = 0; $a < 10; $a++) {
            $roles = $a == 0 ? ["ROLE_ADMIN", "ROLE_USER"] : ["ROLE_USER"];
            $user = new User()
                ->setUuid(sprintf('00000000-0000-0000-0000-00000000%04d', $a))
                ->setRoles($roles);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
