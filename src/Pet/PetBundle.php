<?php

declare(strict_types=1);

namespace App\Pet;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use App\Pet\Infrastructure\DependencyInjection\PetExtension;

final class PetBundle extends Bundle
{
    public function getContainerExtension(): PetExtension
    {
        return new PetExtension();
    }
}
