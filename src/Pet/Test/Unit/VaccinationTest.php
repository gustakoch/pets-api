<?php

declare(strict_types=1);

namespace App\Pet\Test\Unit;

use App\Pet\Domain\Pet;
use App\Pet\Domain\Vaccination;
use PHPUnit\Framework\TestCase;
use App\Shared\Domain\ValueObject\Money;
use App\Veterinarian\Domain\Veterinarian;
use App\Pet\Domain\ValueObject\VaccineType;

class VaccinationTest extends TestCase
{
    public function testCreateVaccination(): void
    {
        $pet = $this->createMock(Pet::class);
        $veterinarian = $this->createMock(Veterinarian::class);
        $type = VaccineType::from('rabies');
        $applicationDate = new \DateTime('2024-01-01');
        $boosterDate = new \DateTime('2024-06-01');
        $price = new Money(5000);

        $vaccination = Vaccination::create(
            'Vaccine X',
            $pet,
            $veterinarian,
            'Vaccine description',
            $type,
            $applicationDate,
            $boosterDate,
            $price,
            'Manufacturer Y',
            'Additional notes'
        );

        $this->assertInstanceOf(Vaccination::class, $vaccination);
        $this->assertEquals('Vaccine X', $vaccination->getName());
        $this->assertSame($pet, $vaccination->getPet());
        $this->assertSame($veterinarian, $vaccination->getVeterinarian());
        $this->assertEquals('Vaccine description', $vaccination->getDescription());
        $this->assertSame($type, $vaccination->getType());
        $this->assertEquals($applicationDate, $vaccination->getApplicationDate());
        $this->assertEquals($boosterDate, $vaccination->getBoosterDate());
        $this->assertEquals(5000, $vaccination->getPrice());
        $this->assertEquals('Manufacturer Y', $vaccination->getManufacturer());
        $this->assertEquals('Additional notes', $vaccination->getNotes());
    }

    public function testUpdateVaccination(): void
    {
        $pet = $this->createMock(Pet::class);
        $veterinarian = $this->createMock(Veterinarian::class);
        $type = VaccineType::from('rabies');
        $applicationDate = new \DateTime('2024-01-01');
        $boosterDate = new \DateTime('2024-06-01');
        $price = new Money(6000);

        $vaccination = Vaccination::create(
            'Vaccine X',
            $pet,
            $veterinarian,
            'Vaccine description',
            $type,
            $applicationDate,
            $boosterDate,
            $price,
            'Manufacturer Y',
            'Additional notes'
        );

        $newApplicationDate = new \DateTime('2024-02-01');
        $newBoosterDate = new \DateTime('2024-07-01');
        $newPrice = new Money(7000);

        $vaccination->update(
            'Vaccine Z',
            $pet,
            $veterinarian,
            'New description',
            $type,
            $newApplicationDate,
            $newBoosterDate,
            $newPrice,
            'New Manufacturer',
            'New notes'
        );

        $this->assertEquals('Vaccine Z', $vaccination->getName());
        $this->assertEquals('New description', $vaccination->getDescription());
        $this->assertEquals($newApplicationDate, $vaccination->getApplicationDate());
        $this->assertEquals($newBoosterDate, $vaccination->getBoosterDate());
        $this->assertEquals(7000, $vaccination->getPrice());
        $this->assertEquals('New Manufacturer', $vaccination->getManufacturer());
        $this->assertEquals('New notes', $vaccination->getNotes());
    }
}
