<?php

declare(strict_types=1);

namespace App\Pet\Test\DataFixtures;

use App\Pet\Domain\Pet;
use App\Pet\Domain\ValueObject\Specie;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class PetsFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $species = Specie::cases();
        foreach (range(1, 10) as $index) {
            $pet = Pet::create(
                name: 'Pet '.$index,
                specie: $species[array_rand($species)],
                birthDate: new \DateTime('2020-01-01'),
                color: 'Brown',
                description: 'Very friendly',
                veterinarians: []
            );
            $manager->persist($pet);
        }
        $manager->flush();
    }
}
