<?php

namespace App\Repository;

use App\Entity\LigneFacture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LigneFacture>
 */
class LigneFactureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LigneFacture::class);
    }

    //    /**
    //     * @return LigneFacture[] Returns an array of LigneFacture objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?LigneFacture
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function occupationChambreClient($client){
        return $this->createQueryBuilder('l')
            ->join('l.facture','f')
            ->join('f.client','c')
            ->join('l.produit','paa')
            ->join('paa.produit','p')
            ->join('p.famille','fa')
            ->andWhere('c.id = :cl')
            ->andWhere('fa.numeroS = :val')
            ->setParameter('val', 3)
            ->setParameter('cl', $client)
            ->getQuery()
            ->getResult()
//            ->getOneOrNullResult()
            ;
    }
}
