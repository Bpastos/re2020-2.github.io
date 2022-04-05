<?php

declare(strict_types=1);

namespace App\Repository\Project;

use App\Entity\Project\SanitaryHotwater;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SanitaryHotwater|null find($id, $lockMode = null, $lockVersion = null)
 * @method SanitaryHotwater|null findOneBy(array $criteria, array $orderBy = null)
 * @method                       findAll()                                                                     array<int, SanitaryHotwater>
 * @method                       findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) array<array-key, SanitaryHotwater>
 *
 * @template T
 *
 * @extends ServiceEntityRepository<SanitaryHotwater>
 */
final class SanitaryHotwaterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SanitaryHotwater::class);
    }
}
