<?php

declare(strict_types=1);

namespace App\Repository\Project;

use App\Entity\Project\MainHeading;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MainHeading|null find($id, $lockMode = null, $lockVersion = null)
 * @method MainHeading|null findOneBy(array $criteria, array $orderBy = null)
 * @method                  findAll()                                                                     array<int, MainHeading>
 * @method                  findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) array<array-key, MainHeading>
 *
 * @template T
 *
 * @extends ServiceEntityRepository<MainHeading>
 */
final class MainHeadingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MainHeading::class);
    }
}
