<?php

declare(strict_types=1);

namespace App\Veterinarian;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use App\Veterinarian\Infrastructure\DependencyInjection\VeterinarianExtension;

final class VeterinarianBundle extends Bundle
{
    public function getContainerExtension(): VeterinarianExtension
    {
        return new VeterinarianExtension();
    }
}
