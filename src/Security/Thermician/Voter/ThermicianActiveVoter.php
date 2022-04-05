<?php

namespace App\Security\Thermician\Voter;

use App\Entity\Thermician\Ticket;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ThermicianActiveVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return 'CAN_EDIT' === $attribute
            && $subject instanceof Ticket;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        /** @var Ticket $subject */
        return $subject->getActiveThermician() === $user;
    }
}
