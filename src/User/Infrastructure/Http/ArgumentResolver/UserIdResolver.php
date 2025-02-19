<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Http\ArgumentResolver;

use Symfony\Component\Uid\Ulid;
use App\User\Domain\ValueObject\UserId;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final class UserIdResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (UserId::class !== $argument->getType()) {
            return [];
        }

        return [new UserId(Ulid::fromString($request->attributes->get($argument->getName())))];
    }
}
