<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Http\Controller;

use App\User\Domain\User;
use OpenApi\Attributes as OA;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\User\Application\Command\User\Handler;
use Symfony\Component\Routing\Attribute\Route;
use App\User\Infrastructure\QueryObject\UserQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Shared\Infrastructure\Http\Response\Response;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken;
use App\Shared\Infrastructure\QueryHandler\QueryExecutor;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/v1/auth', name: 'auth_')]
#[OA\Tag(name: 'Auth')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
final readonly class SecurityController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly Response $response,
        private readonly Handler $handler,
        private readonly QueryExecutor $query,
        private readonly TokenStorageInterface $tokenStorage,
        private readonly RefreshTokenManagerInterface $refreshTokenManager,
        private readonly Security $security,
    ) {
    }

    #[Route('/token-check', name: 'token-check', methods: ['GET'])]
    public function checkToken(): JsonResponse
    {
        return $this->response->view();
    }

    #[Route('/user-profile', name: 'user_profile', methods: ['GET'], stateless: false)]
    #[OA\Put(summary: 'User Profile')]
    #[OA\Response(response: 200, description: 'User Profile')]
    public function userProfile(): JsonResponse
    {
        /** @var User $user */
        $user = $this->security->getUser();

        return $this->response->view(
            $this->query->execute(new UserQuery($user->getId()))
        );
    }

    #[Route('/logout', name: 'logout', methods: ['GET'])]
    public function logout(): JsonResponse
    {
        $token = $this->tokenStorage->getToken();
        if (null === $token) {
            return $this->response->error(
                new NotFoundHttpException('User with such token not found'),
            );
        }
        /** @var User $user */
        $user = $token->getUser();
        $refreshTokens = $this->em->getRepository(RefreshToken::class)->findBy([
            'username' => $user->email(),
        ]);
        foreach ($refreshTokens as $refreshToken) {
            $this->refreshTokenManager->delete($refreshToken);
        }

        return $this->response->view();
    }
}
