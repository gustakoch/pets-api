<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Http\ArgumentResolver;

use Symfony\Component\HttpFoundation\Request;
use App\User\Infrastructure\Filters\RolesFilter;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final class RolesFilterResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): array
    {
        if (RolesFilter::class !== $argument->getType()) {
            return [];
        }

        return [
            new RolesFilter(
                $request->query->get('name') ?: null,
            ),
        ];
    }
}
