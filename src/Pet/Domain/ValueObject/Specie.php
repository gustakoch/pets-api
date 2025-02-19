<?php

declare(strict_types=1);

namespace App\Pet\Domain\ValueObject;

enum Specie: string
{
    case Dog = 'dog';
    case Cat = 'cat';
    case Rabbit = 'rabbit';
    case Hamster = 'hamster';
    case Bird = 'bird';
    case Fish = 'fish';
    case Turtle = 'turtle';
    case GuineaPig = 'guinea_pig';

    public static function values(): array
    {
        return array_map(static fn (self $case) => $case->value, self::cases());
    }
}
