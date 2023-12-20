<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractFixture extends Fixture
{
    /** @var SerializerInterface $serializer */
    private $serializer;

    /** @var string $fixturesPath */
    private $fixturesPath;

    public function __construct(SerializerInterface $serializer, string $fixturesPath)
    {
        $this->serializer = $serializer;
        $this->fixturesPath = $fixturesPath;
    }

    protected function getEntities(string $fileName, string $entityFqcn): array
    {
        $finder = new Finder();
        $finder
            ->files()
            ->in($this->fixturesPath . '/' . $fileName)
            ->name('*.json')
        ;

        $entityLists = [];
        foreach ($finder as $file) {
            $entityLists[] = $this->serializer->deserialize($file->getContents(), $entityFqcn . '[]', 'json');
        }

        return array_merge(...$entityLists);
    }
}
