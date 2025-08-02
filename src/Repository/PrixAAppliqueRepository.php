<?php

namespace App\Repository;

use App\Entity\PrixAApplique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PrixAApplique>
 */
class PrixAAppliqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PrixAApplique::class);
    }

    //    /**
    //     * @return PrixAApplique[] Returns an array of PrixAApplique objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?PrixAApplique
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

//    public function produit()
//    {
//        $queryBuilder = $this->createQueryBuilder('p')
//            ->join('p.service', 's')
//            ->join('p.produit', 'c')
//            ->join('c.famille', 'f')
//
// ->where('f.numeroS IS NULL')
//            ->orWhere('f.numeroS  NOT IN (:libelles)')
//            ->setParameter('libelles', [3,7])
//
//    }

    public function produitParService($service, $famille = null)
    {
        $query = $this->createQueryBuilder('p')
            ->addSelect('s', 'c', 'f')
            ->join('p.service', 's')
            ->join('p.produit', 'c')
            ->join('c.famille', 'f')
            ->andWhere('p.service = :val')
            ->setParameter('val', $service->getId()->toBinary());
        if ($famille) {
            $query
                ->andWhere('f.id = :val1')
                ->setParameter('val1', $famille->getId()->toBinary());
        }
        return
            $query->getQuery()
                ->getResult();
    }

    public function listeEtatChambres($disponible = true)
    {
        return $this->createQueryBuilder('paa')
            ->select('COUNT(paa)')
            ->join('paa.service', 's')
            ->join('paa.produit', 'produit')
            ->join('produit.famille', 'famille')
            ->where('famille.numeroS = :id')
            ->andWhere('produit.estDisponible = :etat')
//            ->andWhere(
//                (new Expr())->orX(
//                    (new Expr())->eq('produit.estDisponible', ':etat'),
//                    (new Expr())->isNotNull('produit.estDisponible')
//                )
//            )
//            ->andWhere(
//                (new Expr())->orX(
//                    'produit.estDisponible IS NULL',
//                    'produit.estDisponible = :etat',
//
//                )
//            )
            ->setParameter('id', '3')
            ->setParameter('etat', $disponible)
            ->getQuery()
            ->getSingleScalarResult();
//            ->getResult();
    }

    public function findProduitFamilleByServiceFeat($service, $admin = false, $limit = null): array
    {

        $qb = $this->createQueryBuilder('p')
            ->innerJoin('p.produit', 'produit')
            ->innerJoin('produit.famille', 'codefamille')
//            ->innerJoin('produit.imagesProduits','ip')
            ->innerJoin('p.service', 'codeservice')
            ->andWhere('codeservice.referenceManuel = :seruce')
//            ->andWhere('produit.feat = true');
//        if (!$admin) {
//            $qb->andWhere('produit.photo != \'\'');
//        }
        ;
        $qb->setParameter('seruce', $service);
        if ($limit != null) {
            $qb->setMaxResults($limit);
        };

        return $qb->getQuery()
            ->getResult();

//        return $qb;
    }

    public function findAvailableRooms(\DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        $qb = $this->createQueryBuilder('p')
            ->innerJoin('p.produit', 'produit')
//            ->innerJoin('produit.imagesProduits', 'images')
            ->leftjoin('produit.famille', 'famille')
            ->leftJoin('p.ligneFactures', 'res')
            ->where('famille.numeroS = :id')
//            ->andwhere('p.referenceManuel = :codeservice')
            ->andwhere('res.dateEntree > :endDate OR res.dateSortie < :startDate OR res.id IS NULL')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('id', '3')
            ->getQuery()
            ->getResult();

        return $qb;
    }

    ////
    public function findMets($admin = false, $feat = false, $limit = null): array
    {

        $qb = $this->createQueryBuilder('p')
            ->innerJoin('p.produit', 'produit')


        ;
        if (!$admin) {
            $qb->innerJoin('produit.imagesProduits', 'images');
        };
        $qb->
        innerJoin('produit.famille', 'f')
            ->where('f.numeroS IS NULL')
            ->orWhere('f.numeroS  NOT IN (:libelles)')
            ->setParameter('libelles', [3,7])
        ;
        if ($feat) {
            $qb
                ->andWhere('produit.feat = true');
        };
        if ($limit != null) {
            $qb->setMaxResults($limit);
        };

        return $qb->getQuery()
            ->getResult();

//        return $qb;
    }


}
