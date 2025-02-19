<?php

declare(strict_types=1);

namespace App\User\Domain\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use App\Shared\Domain\ValueObject\BaseId;

#[ORM\Embeddable]
final class RoleId extends BaseId
{
}
