<?php

declare(strict_types=1);

namespace App\Repository\Thermician;

use App\Entity\Thermician\Ticket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ticket|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ticket|null findOneBy(array $criteria, array $orderBy = null)
 * @method             findAll()                                                                     array<int, Ticket>
 * @method             findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) array<array-key, Ticket>
 *
 * @template T
 *
 * @extends ServiceEntityRepository<\App\Entity\Thermician\Ticket>
 */
final class TicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ticket::class);
    }
}
