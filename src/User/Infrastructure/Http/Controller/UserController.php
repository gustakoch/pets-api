<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Http\Controller;

use OpenApi\Attributes as OA;
use App\User\Domain\ValueObject\UserId;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use Symfony\Bundle\SecurityBundle\Security;
use App\User\Application\Command\User\Update;
use App\User\Application\Command\User\Handler;
use Symfony\Component\Routing\Attribute\Route;
use App\User\Infrastructure\Http\View\UserView;
use App\User\Infrastructure\QueryObject\UserQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\User\Infrastructure\QueryObject\UsersQuery;
use App\Shared\Infrastructure\Http\Response\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Shared\Infrastructure\QueryHandler\QueryExecutor;

#[Route('/api/v1/users', name: 'user_')]
#[OA\Tag(name: 'Users')]
#[IsGranted('CAN_MANAGE_USER')]
final readonly class UserController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly Response $response,
        private readonly Handler $handler,
        private readonly QueryExecutor $query,
        private readonly Security $security,
    ) {
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    #[OA\Get(summary: 'Show User')]
    #[OA\Response(response: 200, description: 'Show User', content: new Model(type: UserView::class))]
    public function show(UserId $id): JsonResponse
    {
        $user = $this->query->execute(new UserQuery($id));
        if (null !== $user) {
            return $this->response->view($user);
        }

        return $this->response->view(code: 404);
    }

    #[Route('', name: 'list', methods: ['GET'])]
    #[OA\Get(summary: 'List Users')]
    #[OA\Response(response: 200, description: 'Users list', content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(ref: new Model(type: UserView::class))
    ))]
    public function list(): JsonResponse
    {
        return $this->response->list(
            $this->query->execute(new UsersQuery())
        );
    }

    #[Route('/{id}/update', name: 'update', methods: ['PUT'])]
    #[OA\Put(summary: 'Update User')]
    #[OA\RequestBody(description: 'Update User', content: new Model(type: Update::class))]
    #[OA\Response(response: 200, description: 'User updated', content: new Model(type: UserView::class))]
    public function update(Update $update): JsonResponse
    {
        try {
            $user = $this->em->wrapInTransaction(
                fn () => $this->handler->update($update)
            );

            return $this->response->view(
                $this->query->execute(new UserQuery($user->getId())),
            );
        } catch (\Exception $exception) {
            return $this->response->error($exception);
        }
    }
}
