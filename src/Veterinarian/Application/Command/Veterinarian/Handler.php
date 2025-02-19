<?php

declare(strict_types=1);

namespace App\Veterinarian\Application\Command\Veterinarian;

use App\Veterinarian\Domain\Veterinarian;
use App\Shared\Application\Validator\Validator;
use App\Veterinarian\Domain\ValueObject\Address;
use App\Veterinarian\Domain\ValueObject\VeterinarianId;
use App\Veterinarian\Domain\Repository\VeterinarianRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class Handler
{
    public function __construct(
        private readonly VeterinarianRepository $repository,
        private readonly Validator $validator,
    ) {
    }

    public function create(Create $create): Veterinarian
    {
        $this->validator->validate($create);
        $veterinarian = Veterinarian::create(
            $create->name,
            $create->email,
            $create->phone,
            $create->hasPhoneWhatsapp,
            $create->specializations,
            new Address(
                $create->address->postalCode,
                $create->address->street,
                $create->address->number,
                $create->address->neighborhood,
                $create->address->city,
                $create->address->state,
                $create->address->complement
            ),
        );
        $this->repository->save($veterinarian);

        return $veterinarian;
    }

    public function update(Update $update): Veterinarian
    {
        $this->validator->validate($update);
        $veterinarian = $this->repository->findOneByIdOrNull($update->id);
        if (null === $veterinarian) {
            throw new NotFoundHttpException('Veterinarian doesn\'t exist');
        }
        $veterinarian->update(
            $update->name,
            $update->email,
            $update->phone,
            $update->hasPhoneWhatsapp,
            $update->specializations,
            new Address(
                $update->address->postalCode,
                $update->address->street,
                $update->address->number,
                $update->address->neighborhood,
                $update->address->city,
                $update->address->state,
                $update->address->complement
            ),
        );
        $this->repository->save($veterinarian);

        return $veterinarian;
    }

    public function delete(VeterinarianId $id): void
    {
        $this->repository->remove($id);
    }
}
