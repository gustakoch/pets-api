<?php

declare(strict_types=1);

namespace App\Pet\Test\DataFixtures;

use App\Pet\Domain\Pet;
use App\Pet\Domain\ValueObject\Specie;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class PetFixture extends Fixture
{
    public const PET_NAME_REFERENCE = 'Rex';

    public function load(ObjectManager $manager): void
    {
        $pet = Pet::create(
            name: 'Rex',
            specie: Specie::from('dog'),
            birthDate: new \DateTime('2020-01-01'),
            color: 'Brown',
            description: 'Very friendly',
            veterinarians: []
        );
        $manager->persist($pet);
        $manager->flush();

        $this->addReference(self::PET_NAME_REFERENCE, $pet);
    }
}
