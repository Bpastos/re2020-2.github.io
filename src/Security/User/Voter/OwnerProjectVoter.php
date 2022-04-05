<?php

declare(strict_types=1);

namespace App\Security\User\Voter;

use App\Entity\Project\Project;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class OwnerProjectVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return 'IS_OWNER' === $attribute
            && $subject instanceof Project;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        /** @var Project $subject */
        return $subject->getUser() === $user;
    }
}
