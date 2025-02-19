<?php

declare(strict_types=1);

namespace App\User\Application\Command\User;

use Symfony\Component\Uid\Ulid;
use App\User\Domain\ValueObject\RoleId;
use App\User\Domain\ValueObject\UserId;
use App\User\Application\Validator\RoleExist;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Validator\Constraints as Assert;

final class Update
{
    #[Assert\NotBlank]
    public string $firstname;

    #[Assert\NotBlank]
    public string $lastname;

    #[RoleExist]
    #[Assert\NotBlank]
    public RoleId $roleId;

    public function __construct(
        #[Assert\NotBlank]
        #[Ignore]
        public UserId $id,
        string $roleId,
    ) {
        $this->roleId = new RoleId(new Ulid($roleId));
    }
}
