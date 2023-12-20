<?php

namespace App\DataFixtures;

use App\Entity\Blog;
use Doctrine\Persistence\ObjectManager;

class BlockFixtures extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        /** @var Blog[] $bookings */
        $blogs = $this->getEntities('Blog', Blog::class);

        foreach ($blogs as $blog) {
            $manager->persist($blog);
        }

        $manager->flush();
    }
}
