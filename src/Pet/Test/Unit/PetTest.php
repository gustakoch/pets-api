<?php

declare(strict_types=1);

namespace App\Pet\Test\Unit;

use App\Pet\Domain\Pet;
use PHPUnit\Framework\TestCase;
use App\Pet\Domain\ValueObject\Specie;
use App\Veterinarian\Domain\Veterinarian;

class PetTest extends TestCase
{
    public function testCreatePet()
    {
        $specie = Specie::from('dog');
        $birthDate = new \DateTime('2020-01-01');
        $pet = Pet::create('Rex', $specie, $birthDate, 'Brown', 'Very friendly', []);

        $this->assertEquals('Rex', $pet->getName());
        $this->assertEquals('dog', $pet->getSpecie()->value);
        $this->assertEquals('Brown', $pet->getColor());
        $this->assertEquals('Very friendly', $pet->getDescription());
        $this->assertCount(0, $pet->getVeterinarians());
    }

    public function testUpdatePet()
    {
        $specie = Specie::from('dog');
        $birthDate = new \DateTime('2020-01-01');
        $pet = Pet::create('Rex', $specie, $birthDate, 'Brown', 'Very friendly', []);

        $newSpecie = Specie::from('cat');
        $newBirthDate = new \DateTime('2021-05-05');
        $pet->update('Milo', $newSpecie, $newBirthDate, 'White', 'Loves to sleep', []);

        $this->assertEquals('Milo', $pet->getName());
        $this->assertEquals('cat', $pet->getSpecie()->value);
        $this->assertEquals('White', $pet->getColor());
        $this->assertEquals('Loves to sleep', $pet->getDescription());
    }

    public function testAddVeterinarian()
    {
        $pet = Pet::create('Rex', Specie::from('dog'), new \DateTime('2020-01-01'), 'Brown', 'Very friendly', []);

        $vet = $this->createMock(Veterinarian::class);
        $pet->addVeterinarian($vet);

        $this->assertCount(1, $pet->getVeterinarians());
        $this->assertTrue($pet->getVeterinarians()->contains($vet));
    }

    public function testRemoveVeterinarian()
    {
        $pet = Pet::create('Rex', Specie::from('dog'), new \DateTime('2020-01-01'), 'Brown', 'Very friendly', []);

        $vet = $this->createMock(Veterinarian::class);
        $pet->addVeterinarian($vet);
        $this->assertCount(1, $pet->getVeterinarians());

        $pet->removeVeterinarian($vet);
        $this->assertCount(0, $pet->getVeterinarians());
    }
}
