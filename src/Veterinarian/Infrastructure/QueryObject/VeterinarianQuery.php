<?php

declare(strict_types=1);

namespace App\Veterinarian\Infrastructure\QueryObject;

use Doctrine\ORM\EntityManagerInterface;
use App\Veterinarian\Domain\Veterinarian;
use App\Shared\Infrastructure\QueryHandler\QueryObject;
use App\Veterinarian\Domain\ValueObject\VeterinarianId;
use App\Veterinarian\Infrastructure\Http\View\VeterinarianView;

final class VeterinarianQuery implements QueryObject
{
    public function __construct(
        private readonly VeterinarianId $veterinarianId,
    ) {
    }

    public function execute(EntityManagerInterface $em): ?VeterinarianView
    {
        return $em->createQueryBuilder()
            ->select(\sprintf('
                new %s (
                    v.id.publicId,
                    v.name,
                    v.email,
                    v.phone,
                    v.hasPhoneWhatsapp,
                    v.specializations,
                    v.address.postalCode,
                    v.address.street,
                    v.address.number,
                    v.address.neighborhood,
                    v.address.city,
                    v.address.state,
                    v.address.complement
                )
            ', VeterinarianView::class))
            ->from(Veterinarian::class, 'v')
            ->andWhere('v.id.publicId = :publicId')
            ->setParameter('publicId', $this->veterinarianId->publicId())
            ->getQuery()
            ->getOneOrNullResult();
    }
}
