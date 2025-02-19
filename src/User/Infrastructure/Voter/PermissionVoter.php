<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class PermissionVoter extends Voter
{
    /**
     * @var string
     */
    private const PREFIX = 'CAN_';

    protected function supports(string $attribute, $subject): bool
    {
        return str_starts_with($attribute, self::PREFIX);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $permissions = $token->getRoleNames();

        return \in_array($attribute, $permissions);
    }
}
