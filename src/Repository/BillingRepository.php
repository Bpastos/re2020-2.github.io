<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Billing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Billing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Billing|null findOneBy(array $criteria, array $orderBy = null)
 * @method              findAll()                                                                     array<int, Billing>
 * @method              findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) array<array-key, Billing>
 *
 * @template T
 *
 * @extends ServiceEntityRepository<Billing>
 */
final class BillingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Billing::class);
    }
}
