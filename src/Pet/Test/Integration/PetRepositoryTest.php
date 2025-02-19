<?php

declare(strict_types=1);

namespace App\Pet\Test\Integration;

use App\Pet\Domain\Pet;
use App\Pet\Domain\ValueObject\Specie;
use App\Shared\Test\BaseKernelTestCase;
use Doctrine\Common\DataFixtures\Loader;
use App\Pet\Test\DataFixtures\PetFixture;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use App\Pet\Infrastructure\Persistence\PetRepository;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;

class PetRepositoryTest extends BaseKernelTestCase
{
    private PetRepository $petRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->petRepository = new PetRepository($this->entityManager);
    }

    public function testSavePet(): void
    {
        $pet = Pet::create(
            name: 'Rex',
            specie: Specie::from('dog'),
            birthDate:new \DateTime('2020-01-01'),
            color: 'Brown',
            description: 'Very friendly',
            veterinarians: [],
        );
        $this->petRepository->save($pet);
        $this->entityManager->flush();

        $savedPet = $this->petRepository->findOneByIdOrNull($pet->getId());

        $this->assertNotNull($savedPet);
        $this->assertEquals('Rex', $savedPet->getName());
        $this->assertEquals('dog', $savedPet->getSpecie()->value);
        $this->assertEquals('Brown', $savedPet->getColor());
        $this->assertEquals('Very friendly', $savedPet->getDescription());
    }

    public function testUpdatePet(): void
    {
        $pet = Pet::create(
            name: 'Rex',
            specie: Specie::from('dog'),
            birthDate: new \DateTime('2020-01-01'),
            color: 'Brown',
            description: 'Very friendly',
            veterinarians: [],
        );
        $this->petRepository->save($pet);
        $this->entityManager->flush();

        $pet->update(
            name: 'Milo',
            specie: Specie::from('cat'),
            birthDate: new \DateTime('2021-05-05'),
            color: 'White',
            description: 'Loves to sleep',
            veterinarians: [],
        );
        $this->petRepository->save($pet);
        $this->entityManager->flush();

        $updatedPet = $this->petRepository->findOneByIdOrNull($pet->getId());

        $this->assertNotNull($updatedPet);
        $this->assertEquals('Milo', $updatedPet->getName());
        $this->assertEquals('cat', $updatedPet->getSpecie()->value);
        $this->assertEquals('White', $updatedPet->getColor());
        $this->assertEquals('Loves to sleep', $updatedPet->getDescription());
    }

    public function testFindPetByName(): void
    {
        $this->loadFixtures();
        $pet = $this->petRepository->findOneByOrNull(['name' => PetFixture::PET_NAME_REFERENCE]);

        $this->assertNotNull($pet);
        $this->assertEquals(PetFixture::PET_NAME_REFERENCE, $pet->getName());
    }

    public function testFindPetByNameIsNull(): void
    {
        $this->loadFixtures();
        $pet = $this->petRepository->findOneByOrNull(['name' => 'Joey']);

        $this->assertEquals(null, $pet);
    }

    private function loadFixtures(): void
    {
        $loader = new Loader();
        $loader->addFixture(new PetFixture());

        $executor = new ORMExecutor($this->entityManager, new ORMPurger());
        $executor->execute($loader->getFixtures());
    }
}
