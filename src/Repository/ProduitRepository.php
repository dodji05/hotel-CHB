<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[] findAll()
 * @method Produit[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function findProduitsRestaurant($value) : array
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.prixAAppliquers', 'aa')
            ->innerJoin('aa.codeservice', 'codeservice')
            ->where('codeservice.id = :val')
            ->setParameter('val', $value)
            ->orderBy('p.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function produitEnstockDeReapprovisionnement(){
        return $this->createQueryBuilder('p')
            ->select('count(p.stockactuel -p.qteappro) as  Dif')
            ->andWhere('p.eststockable = 0')
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function produitEnstockAlerte(){
        return $this->createQueryBuilder('p')
            ->select('count(p.stockactuel -p.qtemini) as  Dif')
            ->andWhere('p.stockactuel -p.qtemini <= 0')
        //    ->groupBy('p.reference')
            ->getQuery()
           ->getOneOrNullResult()
         // ->getSingleScalarResult()
            ;
    }


}
