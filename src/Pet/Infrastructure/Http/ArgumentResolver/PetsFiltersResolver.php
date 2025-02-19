<?php

declare(strict_types=1);

namespace App\Pet\Infrastructure\Http\ArgumentResolver;

use Symfony\Component\HttpFoundation\Request;
use App\Pet\Infrastructure\Filters\PetsFilters;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final class PetsFiltersResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): array
    {
        if (PetsFilters::class !== $argument->getType()) {
            return [];
        }

        return [
            new PetsFilters(
                name: $request->query->get('name') ?: null,
                specie: $request->query->get('specie') ?: null,
            ),
        ];
    }
}
