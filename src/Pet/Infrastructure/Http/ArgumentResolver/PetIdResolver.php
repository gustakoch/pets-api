<?php

declare(strict_types=1);

namespace App\Pet\Infrastructure\Http\ArgumentResolver;

use Symfony\Component\Uid\Ulid;
use App\Pet\Domain\ValueObject\PetId;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final class PetIdResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (PetId::class !== $argument->getType()) {
            return [];
        }

        return [new PetId(Ulid::fromString($request->attributes->get($argument->getName())))];
    }
}
