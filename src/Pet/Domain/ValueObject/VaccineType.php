<?php

declare(strict_types=1);

namespace App\Pet\Domain\ValueObject;

enum VaccineType: string
{
    case CanineDistemperVirus = 'canine_distemper_virus';
    case CanineParvovirus = 'canine_parvovirus';
    case CanineAdenovirus2 = 'canine_adenovirus_2';
    case CanineParainfluenzaVirus = 'canine_parainfluenza_virus';
    case FelineCalicivirus = 'feline_calicivirus';
    case FelineHerpesvirus1 = 'feline_herpesvirus_1';
    case FelinePanleukopeniaVirus = 'feline_panleukopenia_virus';
    case FelineLeukemiaVirus = 'feline_leukemia_virus';
    case Rabies = 'rabies';

    public static function values(): array
    {
        return array_map(static fn (self $case) => $case->value, self::cases());
    }
}
