<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        /** @var User[] $bookings */
        $users = $this->getEntities('User', User::class);

        foreach ($users as $user) {
            $manager->persist($user);
        }

        $manager->flush();
    }
}
