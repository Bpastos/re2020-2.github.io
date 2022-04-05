<?php

declare(strict_types=1);

namespace App\Repository\Project;

use App\Entity\Project\Carpentry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Carpentry|null find($id, $lockMode = null, $lockVersion = null)
 * @method Carpentry|null findOneBy(array $criteria, array $orderBy = null)
 * @method                findAll()                                                                     array<int, Carpentry>
 * @method                findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) array<array-key, Carpentry>
 *
 * @template T
 *
 * @extends ServiceEntityRepository<Carpentry>
 */
final class CarpentryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Carpentry::class);
    }
}
