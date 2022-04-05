<?php

declare(strict_types=1);

namespace App\Repository\Project;

use App\Entity\Project\SecondaryHeading;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SecondaryHeading|null find($id, $lockMode = null, $lockVersion = null)
 * @method SecondaryHeading|null findOneBy(array $criteria, array $orderBy = null)
 * @method                       findAll()                                                                     array<int, SecondaryHeading>
 * @method                       findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) array<array-key, SecondaryHeading>
 *
 * @template T
 *
 * @extends ServiceEntityRepository<\App\Entity\Project\SecondaryHeading>
 */
final class SecondaryHeadingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SecondaryHeading::class);
    }
}
