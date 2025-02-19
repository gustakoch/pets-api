<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Http\Controller;

use OpenApi\Attributes as OA;
use App\User\Domain\ValueObject\RoleId;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use App\User\Application\Command\Role\Create;
use App\User\Application\Command\Role\Update;
use App\User\Application\Command\Role\Handler;
use Symfony\Component\Routing\Attribute\Route;
use App\User\Infrastructure\Http\View\RoleView;
use App\User\Infrastructure\Filters\RolesFilter;
use App\User\Infrastructure\QueryObject\RoleQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\User\Infrastructure\QueryObject\RolesQuery;
use App\Shared\Infrastructure\Http\Response\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Shared\Infrastructure\QueryHandler\QueryExecutor;

#[Route('/api/v1/roles', name: 'role_')]
#[OA\Tag(name: 'Roles')]
#[IsGranted('CAN_MANAGE_USER')]
final readonly class RoleController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly Response $response,
        private readonly Handler $handler,
        private readonly QueryExecutor $query,
    ) {
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    #[OA\Get(summary: 'Show Role')]
    #[OA\Response(response: 200, description: 'Show Role', content: new Model(type: RoleView::class))]
    public function show(RoleId $id): JsonResponse
    {
        $role = $this->query->execute(new RoleQuery($id));
        if (null !== $role) {
            return $this->response->view($role);
        }

        return $this->response->view(code: 404);
    }

    #[Route('', name: 'show', methods: ['GET'])]
    #[OA\Get(summary: 'List Of Roles')]
    #[OA\Response(response: 200, description: 'Roles list', content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(ref: new Model(type: RoleView::class))
    ))]
    #[OA\Parameter(name: 'name', in: 'query', description: 'Filter by Name', required: false, schema: new OA\Schema(type: 'string'))]
    public function list(RolesFilter $filters): JsonResponse
    {
        return $this->response->view(
            $this->query->execute(new RolesQuery($filters))
        );
    }

    #[Route('', name: 'create', methods: ['POST'])]
    #[OA\Post(summary: 'Create Role')]
    #[OA\RequestBody(description: 'Create Role', content: new Model(type: Create::class))]
    #[OA\Response(response: 201, description: 'Role created', content: new Model(type: RoleView::class))]
    public function create(Create $create): JsonResponse
    {
        try {
            $role = $this->em->wrapInTransaction(
                fn () => $this->handler->create($create)
            );

            return $this->response->view(
                $this->query->execute(new RoleQuery($role->getId())),
                code: 201
            );
        } catch (\Exception $exception) {
            return $this->response->error($exception);
        }
    }

    #[Route('/{id}/update', name: 'update', methods: ['PUT'])]
    #[OA\Put(summary: 'Update Role')]
    #[OA\RequestBody(description: 'Update Role', content: new Model(type: Update::class))]
    #[OA\Response(response: 200, description: 'Role updated', content: new Model(type: RoleView::class))]
    public function update(Update $update): JsonResponse
    {
        try {
            $role = $this->em->wrapInTransaction(
                fn () => $this->handler->update($update)
            );

            return $this->response->view(
                $this->query->execute(new RoleQuery($role->getId()))
            );
        } catch (\Exception $exception) {
            return $this->response->error($exception);
        }
    }
}
