<?php

declare(strict_types=1);

namespace App\Pet\Infrastructure\Http\ArgumentResolver;

use Symfony\Component\Uid\Ulid;
use App\Pet\Domain\ValueObject\VaccinationId;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final class VaccinationIdResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (VaccinationId::class !== $argument->getType()) {
            return [];
        }

        return [new VaccinationId(Ulid::fromString($request->attributes->get($argument->getName())))];
    }
}
