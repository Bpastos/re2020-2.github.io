<?php

declare(strict_types=1);

namespace App\Repository\Project;

use App\Entity\Project\Building;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Building|null find($id, $lockMode = null, $lockVersion = null)
 * @method Building|null findOneBy(array $criteria, array $orderBy = null)
 * @method               findAll()                                                                     array<int, Building>
 * @method               findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) array<array-key, Building>
 *
 * @template T
 *
 * @extends ServiceEntityRepository<Building>
 */
final class BuildingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Building::class);
    }
}
