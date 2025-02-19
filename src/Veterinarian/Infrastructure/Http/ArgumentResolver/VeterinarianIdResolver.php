<?php

declare(strict_types=1);

namespace App\Veterinarian\Infrastructure\Http\ArgumentResolver;

use Symfony\Component\Uid\Ulid;
use Symfony\Component\HttpFoundation\Request;
use App\Veterinarian\Domain\ValueObject\VeterinarianId;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final class VeterinarianIdResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (VeterinarianId::class !== $argument->getType()) {
            return [];
        }

        return [new VeterinarianId(Ulid::fromString($request->attributes->get($argument->getName())))];
    }
}
