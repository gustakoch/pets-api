<?php

declare(strict_types=1);

namespace App\Pet\Infrastructure\Http\Controller;

use App\Pet\Domain\Pet;
use OpenApi\Attributes as OA;
use App\Pet\Domain\ValueObject\PetId;
use App\Pet\Domain\ValueObject\Specie;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use App\Pet\Application\Command\Pet\Create;
use App\Pet\Application\Command\Pet\Update;
use App\Pet\Application\Command\Pet\Handler;
use App\Pet\Infrastructure\Http\View\PetView;
use Symfony\Component\Routing\Attribute\Route;
use App\Pet\Infrastructure\Filters\PetsFilters;
use App\Pet\Infrastructure\QueryObject\PetQuery;
use App\Pet\Infrastructure\QueryObject\PetsQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Shared\Infrastructure\Http\Response\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Shared\Infrastructure\QueryHandler\QueryExecutor;

#[Route('/api/v1/pets', name: 'pet_')]
#[OA\Tag(name: 'Pets')]
#[IsGranted('CAN_MANAGE_PET')]
final readonly class PetController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly Response $response,
        private readonly Handler $handler,
        private readonly QueryExecutor $query,
    ) {
    }

    #[Route('', name: 'list', methods: ['GET'])]
    #[OA\Get(summary: 'List Pets')]
    #[OA\Parameter(name: 'name', in: 'query', description: 'Filter by Name', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'specie', in: 'query', description: 'Filter by Specie', required: false, schema: new OA\Schema(
        type: 'string',
        enum: [Specie::Cat, Specie::Dog, Specie::Rabbit, Specie::Hamster, Specie::Bird, Specie::Fish, Specie::Turtle, Specie::GuineaPig]
    ))]
    #[OA\Response(response: 200, description: 'List Pets', content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(ref: new Model(type: PetView::class))
    ))]
    public function list(PetsFilters $filters): JsonResponse
    {
        return $this->response->list(
            $this->query->execute(new PetsQuery($filters))
        );
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    #[OA\Get(summary: 'Show Pet')]
    #[OA\Response(response: 200, description: 'Show Pet', content: new Model(type: PetView::class))]
    public function show(PetId $id): JsonResponse
    {
        return $this->response->view(
            $this->query->execute(new PetQuery($id))
        );
    }

    #[Route('', name: 'create', methods: ['POST'])]
    #[OA\Post(summary: 'Create Pet')]
    #[OA\RequestBody(description: 'Create Pet', content: new Model(type: Create::class))]
    #[OA\Response(response: 201, description: 'Pet created', content: new Model(type: PetView::class))]
    public function create(Create $create): JsonResponse
    {
        try {
            /** @var Pet */
            $pet = $this->em->wrapInTransaction(
                fn (): Pet => $this->handler->create($create)
            );

            return $this->response->view(
                $this->query->execute(new PetQuery($pet->getId())),
                201
            );
        } catch (\Exception $exception) {
            return $this->response->error($exception);
        }
    }

    #[Route('/{id}/update', name: 'update', methods: ['PUT'])]
    #[OA\Put(summary: 'Update Pet')]
    #[OA\RequestBody(description: 'Update Pet', content: new Model(type: Update::class))]
    #[OA\Response(response: 200, description: 'Pet updated', content: new Model(type: PetView::class))]
    public function update(Update $update): JsonResponse
    {
        try {
            /** @var Pet */
            $pet = $this->em->wrapInTransaction(
                fn (): Pet => $this->handler->update($update)
            );

            return $this->response->view(
                $this->query->execute(new PetQuery($pet->getId()))
            );
        } catch (\Exception $exception) {
            return $this->response->error($exception);
        }
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['DELETE'])]
    #[OA\Delete(summary: 'Delete Pet')]
    #[OA\Response(response: 204, description: 'Pet deleted')]
    public function delete(PetId $id): JsonResponse
    {
        try {
            $this->em->wrapInTransaction(fn () => $this->handler->delete($id));

            return $this->response->view(code: 204);
        } catch (\Exception $exception) {
            return $this->response->error($exception);
        }
    }
}
