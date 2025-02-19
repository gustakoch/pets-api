<?php

declare(strict_types=1);

namespace App\Veterinarian\Infrastructure\Http\Controller;

use OpenApi\Attributes as OA;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use App\Veterinarian\Domain\Veterinarian;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Shared\Infrastructure\Http\Response\Response;
use App\Veterinarian\Domain\ValueObject\VeterinarianId;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Shared\Infrastructure\QueryHandler\QueryExecutor;
use App\Veterinarian\Application\Command\Veterinarian\Create;
use App\Veterinarian\Application\Command\Veterinarian\Update;
use App\Veterinarian\Application\Command\Veterinarian\Handler;
use App\Veterinarian\Infrastructure\Http\View\VeterinarianView;
use App\Veterinarian\Infrastructure\QueryObject\VeterinarianQuery;
use App\Veterinarian\Infrastructure\QueryObject\VeterinariansQuery;

#[Route('/api/v1/veterinarians', name: 'veterinarian_')]
#[OA\Tag(name: 'Veterinarians')]
#[IsGranted('CAN_MANAGE_VETERINARIAN')]
final readonly class VeterinarianController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly Response $response,
        private readonly Handler $handler,
        private readonly QueryExecutor $query,
    ) {
    }

    #[Route('', name: 'list', methods: ['GET'])]
    #[OA\Get(summary: 'List Veterinarians')]
    #[OA\Response(response: 200, description: 'Veterinarians list', content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(ref: new Model(type: VeterinarianView::class))
    ))]
    public function list(): JsonResponse
    {
        return $this->response->list(
            $this->query->execute(new VeterinariansQuery())
        );
    }

    #[Route('/{id}', name: 'get', methods: ['GET'])]
    #[OA\Get(summary: 'Show Veterinarian')]
    #[OA\Response(response: 200, description: 'Veterinarian created', content: new Model(type: VeterinarianView::class))]
    public function show(VeterinarianId $id): JsonResponse
    {
        $veterinarian = $this->query->execute(new VeterinarianQuery($id));
        if (null !== $veterinarian) {
            return $this->response->view($veterinarian);
        }

        return $this->response->view(code: 404);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    #[OA\Post(summary: 'Create Veterinarian')]
    #[OA\RequestBody(description: 'Create Veterinarian', content: new Model(type: Create::class))]
    #[OA\Response(response: 201, description: 'Veterinarian created', content: new Model(type: VeterinarianView::class))]
    public function create(Create $create): JsonResponse
    {
        try {
            /** @var Veterinarian */
            $veterinarian = $this->em->wrapInTransaction(
                fn (): Veterinarian => $this->handler->create($create)
            );

            return $this->response->view(
                data: $this->query->execute(new VeterinarianQuery($veterinarian->getId())),
                code: 201
            );
        } catch (\Exception $exception) {
            return $this->response->error($exception);
        }
    }

    #[Route('/{id}/update', name: 'update', methods: ['PUT'])]
    #[OA\Put(summary: 'Update Veterinarian')]
    #[OA\RequestBody(description: 'Update Veterinarian', content: new Model(type: Update::class))]
    #[OA\Response(response: 200, description: 'Veterinarian updated', content: new Model(type: VeterinarianView::class))]
    public function update(Update $update): JsonResponse
    {
        try {
            /** @var Veterinarian */
            $veterinarian = $this->em->wrapInTransaction(
                fn (): Veterinarian => $this->handler->update($update)
            );

            return $this->response->view(
                $this->query->execute(new VeterinarianQuery($veterinarian->getId())),
            );
        } catch (\Exception $exception) {
            return $this->response->error($exception);
        }
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['DELETE'])]
    #[OA\Delete(summary: 'Delete Veterinarian')]
    #[OA\Response(response: 204, description: 'Veterinarian deleted')]
    public function delete(VeterinarianId $id): JsonResponse
    {
        try {
            $this->em->wrapInTransaction(fn () => $this->handler->delete($id));

            return $this->response->view(code: 204);
        } catch (\Exception $exception) {
            return $this->response->error($exception);
        }
    }
}
