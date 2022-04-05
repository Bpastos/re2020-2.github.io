<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Offer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Offer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offer|null findOneBy(array $criteria, array $orderBy = null)
 * @method            findAll()                                                                     array<int, Offer>
 * @method            findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) array<array-key, Offer>
 *
 * @template T
 *
 * @extends ServiceEntityRepository<Offer>
 */
final class OfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offer::class);
    }
}
