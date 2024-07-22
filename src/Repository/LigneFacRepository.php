<?php

namespace App\Repository;

use App\Entity\LigneFac;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LigneFac|null find($id, $lockMode = null, $lockVersion = null)
 * @method LigneFac|null findOneBy(array $criteria, array $orderBy = null)
 * @method LigneFac[] findAll()
 * @method LigneFac[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LigneFacRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LigneFac::class);
    }



}
