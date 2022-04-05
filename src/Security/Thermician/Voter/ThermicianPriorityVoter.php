<?php

namespace App\Security\Thermician\Voter;

use App\Entity\Thermician\Thermician;
use App\Entity\Thermician\Ticket;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ThermicianPriorityVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return 'IS_PRIORITY' === $attribute
            && $subject instanceof Ticket;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var Thermician $thermician */
        $thermician = $token->getUser();
        /** @var Ticket $subject */
        $oldThermician = $subject->getOldThermician();

        return $thermician === $oldThermician;
    }
}
