<?php

declare(strict_types=1);

namespace App\Repository\Project;

use App\Entity\Project\Ventilation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ventilation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ventilation|null findOneBy(array $criteria, array $orderBy = null)
 * @method                  findAll()                                                                     array<int, Ventilation>
 * @method                  findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) array<array-key, Ventilation>
 *
 * @template T
 *
 * @extends ServiceEntityRepository<\App\Entity\Project\Ventilation>
 */
final class VentilationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ventilation::class);
    }
}
