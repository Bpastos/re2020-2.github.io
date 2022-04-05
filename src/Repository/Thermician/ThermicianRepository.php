<?php

declare(strict_types=1);

namespace App\Repository\Thermician;

use App\Entity\Thermician\Thermician;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method Thermician|null find($id, $lockMode = null, $lockVersion = null)
 * @method Thermician|null findOneBy(array $criteria, array $orderBy = null)
 * @method                 findAll()                                         array<int, Thermician>
 * @method                 findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)array<array-key,Thermician>
 *
 * @template T
 *
 * @extends ServiceEntityRepository<Thermician>
 */
final class ThermicianRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Thermician::class);
    }
}
