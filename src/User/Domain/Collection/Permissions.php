<?php

declare(strict_types=1);

namespace App\User\Domain\Collection;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @template-extends ArrayCollection<int, string>
 */
final class Permissions extends ArrayCollection
{
    public const DICTIONARY = [
        'CAN_MANAGE_VETERINARIAN' => 'Manage Veterinarian',
        'CAN_MANAGE_PET' => 'Manage Pet',
        'CAN_MANAGE_VACCINATION' => 'Manage Vaccination',
        'CAN_MANAGE_USER' => 'Manage User',
        'CAN_MANAGE_MEDICINES' => 'Manage Medicines',
    ];

    public const AVAILABLE = [
        'CAN_MANAGE_VETERINARIAN',
        'CAN_MANAGE_PET',
        'CAN_MANAGE_VACCINATION',
        'CAN_MANAGE_MEDICINES',
        'CAN_MANAGE_USER',
    ];
}
