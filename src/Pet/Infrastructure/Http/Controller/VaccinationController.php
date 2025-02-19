<?php

declare(strict_types=1);

namespace App\Pet\Infrastructure\Http\Controller;

use OpenApi\Attributes as OA;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use App\Pet\Domain\ValueObject\VaccinationId;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Pet\Application\Command\Vaccination\Create;
use App\Pet\Application\Command\Vaccination\Update;
use App\Pet\Application\Command\Vaccination\Handler;
use App\Pet\Infrastructure\Http\View\VaccinationView;
use App\Shared\Infrastructure\Http\Response\Response;
use App\Pet\Infrastructure\QueryObject\VaccinationQuery;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Pet\Infrastructure\QueryObject\VaccinationsQuery;
use App\Shared\Infrastructure\QueryHandler\QueryExecutor;

#[Route('/api/v1/vaccinations', name: 'vaccination_')]
#[OA\Tag(name: 'Vaccinations')]
#[IsGranted('CAN_MANAGE_VACCINATION')]
final readonly class VaccinationController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly Response $response,
        private readonly Handler $handler,
        private readonly QueryExecutor $query,
    ) {
    }

    #[Route('', name: 'list', methods: ['GET'])]
    #[OA\Get(summary: 'List Vaccination')]
    #[OA\Response(response: 200, description: 'List Pets', content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(ref: new Model(type: VaccinationView::class))
    ))]
    public function list(): JsonResponse
    {
        return $this->response->view(
            $this->query->execute(new VaccinationsQuery())
        );
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    #[OA\Get(summary: 'Show Vaccination')]
    #[OA\Response(response: 200, description: 'Show Vaccination', content: new Model(type: VaccinationView::class))]
    public function show(VaccinationId $id): JsonResponse
    {
        return $this->response->view(
            $this->query->execute(new VaccinationQuery($id))
        );
    }

    #[Route('', name: 'create', methods: ['POST'])]
    #[OA\Post(summary: 'Create Vaccination')]
    #[OA\RequestBody(description: 'Create Vaccination', content: new Model(type: Create::class))]
    #[OA\Response(response: 201, description: 'Vaccination created', content: new Model(type: VaccinationView::class))]
    public function create(Create $create): JsonResponse
    {
        try {
            $vaccination = $this->em->wrapInTransaction(
                fn () => $this->handler->create($create)
            );

            return $this->response->view(
                $this->query->execute(new VaccinationQuery($vaccination->getId())),
                201
            );
        } catch (\Exception $exception) {
            return $this->response->error($exception);
        }
    }

    #[Route('/{id}/update', name: 'update', methods: ['PUT'])]
    #[OA\Put(summary: 'Update Vaccination')]
    #[OA\RequestBody(description: 'Update Vaccination', content: new Model(type: Update::class))]
    #[OA\Response(response: 200, description: 'Vaccination updated', content: new Model(type: VaccinationView::class))]
    public function update(Update $update): JsonResponse
    {
        try {
            $vaccination = $this->em->wrapInTransaction(
                fn () => $this->handler->update($update)
            );

            return $this->response->view(
                $this->query->execute(new VaccinationQuery($vaccination->getId()))
            );
        } catch (\Exception $exception) {
            return $this->response->error($exception);
        }
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['DELETE'])]
    #[OA\Delete(summary: 'Delete Vaccination')]
    #[OA\Response(response: 204, description: 'Vaccination deleted')]
    public function delete(VaccinationId $id): JsonResponse
    {
        try {
            $this->em->wrapInTransaction(fn () => $this->handler->delete($id));

            return $this->response->view(code: 204);
        } catch (\Exception $exception) {
            return $this->response->error($exception);
        }
    }
}
