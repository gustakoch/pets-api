<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Http\ArgumentResolver;

use Symfony\Component\Uid\Ulid;
use App\User\Domain\ValueObject\RoleId;
use App\User\Application\Command\Role\Create;
use App\User\Application\Command\Role\Update;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final class RoleResolver implements ValueResolverInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer,
    ) {
    }

    private function supports(ArgumentMetadata $argument): bool
    {
        return \in_array($argument->getType(), [
            Create::class,
            Update::class,
        ]);
    }

    public function resolve(Request $request, ArgumentMetadata $argument): array
    {
        if (!$this->supports($argument)) {
            return [];
        }
        $id = [];
        if (null !== $request->get('id')) {
            $id = [
                'default_constructor_arguments' => [
                    $argument->getType() => [
                        'id' => new RoleId(new Ulid($request->get('id'))),
                    ],
                ],
            ];
        }

        return [$this->serializer->deserialize($request->getContent(), $argument->getType(), 'json', $id)];
    }
}
