<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr;
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

    public function produitSimple($stockacble=null) {
        return $this->createQueryBuilder('p')
//            ->addSelect('f')
            ->innerJoin('p.famille', 'f')
            ->where('f.numeroS IS NULL')
            ->orWhere('f.numeroS  NOT IN (:numero)')
            ->setParameter('numero', [3,7])
            ->getQuery()
            ->getResult()
            ;
    }
    public function produitSimpleRuptureStock($stockacble=true) {
        return $this->createQueryBuilder('p')
            ->addSelect('p','f')
            ->leftJoin('p.famille', 'f')
            ->where('p.estStockable = :stockable')
            ->setParameter('stockable', $stockacble)
            ->andWhere(
                (new Expr())->orX(
                    (new Expr())->notIn('f.numeroS', ':numero'),
                    (new Expr())->isNull('f.numeroS')
                )
            )

            ->andWhere('p.stockActuel < p.qteReapprovissement OR p.stockActuel IS NULL')
            ->setParameter('numero', [3,7])
            ->getQuery()
            ->getResult()
            ;
    }


    public function produitSimpleRuptureStock2($stockacble=true) {
        return $this->createQueryBuilder('p')
            ->addSelect('p','f')
            ->leftJoin('p.famille', 'f')
            ->where('p.estStockable = :stockable')
            ->setParameter('stockable', $stockacble)
            ->andWhere(
                (new Expr())->orX(
                    (new Expr())->notIn('f.numeroS', ':numero'),
                    (new Expr())->isNull('f.numeroS')
                )
            )

            ->andWhere('p.stockActuel < p.stockAlerte OR p.stockActuel IS NULL')
            ->setParameter('numero', [3,7])
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * Récupère les produits d'une catégorie qui n'ont pas de prix appliqué pour un service donné
     *
     * @param string $categorieId UUID de la catégorie (Famille)
     * @param string $serviceId UUID du service
     * @return Produit[]
     */
    public function findProduitsNonAffectesAuService($categorieId, $serviceId): array
    {


        // Sous-requête pour obtenir les IDs des produits déjà associés au service
        $subQb = $this->createQueryBuilder('p')
            ->select('p.id')
            ->innerJoin('p.famille','f')
            ->innerJoin('p.prixAAppliques', 'paa')
            ->leftJoin('paa.service', 's')
            ->where('s.id = :serviceId')
            ->andWhere('f.id = :categorieId')
            ->setParameter('serviceId', $serviceId->getId()->toBinary())
            ->setParameter('categorieId', $categorieId->getId()->toBinary())
            ->getQuery()
//            ->getResult()
            ->getScalarResult();;
//dd($subQb);

        // Requête principale
        $qb = $this->createQueryBuilder('p')
            ->innerjoin('p.famille','f')
            ->innerjoin('p.prixAAppliques', 'paa')
            ->innerjoin('paa.service', 's')
            ->andWhere('f.id = :categorieId')
            ->setParameter('categorieId', $categorieId->getId()->toBinary());

//            $idsAffectes = $subQb->getResult();
        if (count($subQb) > 0) {
            $qb
                ->andWhere('p.id NOT IN (:list)' )
                ->setParameter('list', $subQb);
        }



        ;

        return $qb->getQuery()->getResult();
    }

    public function produitParservice($serviceId){

        return $this->createQueryBuilder('p')
            ->leftJoin('p.prixAAppliques', 'paa')
            ->leftJoin('paa.service', 's')
            ->where('s.id = :id')
            ->setParameter('id', $serviceId->getId()->toBinary())
            ->getQuery()
            ->getResult();
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
