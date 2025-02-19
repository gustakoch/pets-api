<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Http\Controller;

use OpenApi\Attributes as OA;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use App\User\Application\Command\User\Handler;
use Symfony\Component\Routing\Attribute\Route;
use App\User\Application\Command\User\Register;
use App\User\Infrastructure\Http\View\UserView;
use App\User\Infrastructure\QueryObject\UserQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Shared\Infrastructure\Http\Response\Response;
use App\Shared\Infrastructure\QueryHandler\QueryExecutor;

#[Route('/api/v1', name: 'user_')]
#[OA\Tag(name: 'Users')]
final readonly class RegisterController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly Response $response,
        private readonly Handler $handler,
        private readonly QueryExecutor $query,
    ) {
    }

    #[Route('/register/user', name: 'register', methods: ['POST'])]
    #[OA\Post(summary: 'Register User')]
    #[OA\RequestBody(description: 'Register User', content: new Model(type: Register::class))]
    #[OA\Response(response: 201, description: 'User registered', content: new Model(type: UserView::class))]
    public function create(Register $register): JsonResponse
    {
        try {
            $user = $this->em->wrapInTransaction(
                fn () => $this->handler->register($register)
            );

            return $this->response->view(
                $this->query->execute(new UserQuery($user->getId())),
                201
            );
        } catch (\Exception $exception) {
            return $this->response->error($exception);
        }
    }
}
