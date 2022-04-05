<?php

declare(strict_types=1);

namespace App\Repository\Thermician;

use App\Entity\Thermician\Document;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Document|null find($id, $lockMode = null, $lockVersion = null)
 * @method Document|null findOneBy(array $criteria, array $orderBy = null)
 * @method               findAll()                                                                     array<int, Document>
 * @method               findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) array<array-key, Document>
 *
 * @template T
 *
 * @extends ServiceEntityRepository<Document>
 */
final class DocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Document::class);
    }
}
