<?php

declare(strict_types=1);

namespace App\Veterinarian\Infrastructure\QueryObject;

use Doctrine\Common\Collections\Order;
use Doctrine\ORM\EntityManagerInterface;
use App\Veterinarian\Domain\Veterinarian;
use App\Shared\Infrastructure\QueryHandler\QueryObject;
use App\Veterinarian\Infrastructure\Http\View\VeterinarianView;

final class VeterinariansQuery implements QueryObject
{
    /**
     * @return VeterinarianView[]
     */
    public function execute(EntityManagerInterface $em): array
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
            ->orderBy('v.name', Order::Ascending->value)
            ->getQuery()
            ->getResult();
    }
}
