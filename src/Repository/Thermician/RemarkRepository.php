<?php

declare(strict_types=1);

namespace App\Repository\Thermician;

use App\Entity\Thermician\Remark;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Remark|null find($id, $lockMode = null, $lockVersion = null)
 * @method Remark|null findOneBy(array $criteria, array $orderBy = null)
 * @method             findAll()                                                                     array<int, Remark>
 * @method             findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) array<array-key, Remark>
 *
 * @template T
 *
 * @extends ServiceEntityRepository<Remark>
 */
final class RemarkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Remark::class);
    }
}
