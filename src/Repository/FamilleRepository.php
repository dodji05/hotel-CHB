<?php

namespace App\Repository;

use App\Entity\Famille;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Famille>
 * @method Famille|null find($id, $lockMode = null, $lockVersion = null)
 * @method Famille|null findOneBy(array $criteria, array $orderBy = null)
 * @method Famille[] findAll()
 * @method Famille[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FamilleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Famille::class);
    }

    public function findFamilleBySlugOrCodeFamille($slug){
        return $this->createQueryBuilder('f')
            ->andwhere('f.slug = :slug')
            ->orWhere('f.referenceManuel = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findTypeHebergment(){
        return $this->createQueryBuilder('f')
            ->leftJoin('f.produits','p')
            ->andwhere('f.numeroS = 3')
//            ->orWhere('f.codeFamille = :slug')
//            ->setParameter('slug', $slug)
            ->getQuery()
            ->getResult()
         //   ->getOneOrNullResult()
            ;
    }

    public function familleRestaurant()
    {
        return $this->createQueryBuilder('f')
            ->where('f.numeroS IS NULL')
            ->orWhere('f.numeroS  NOT IN (:libelles)')
            ->setParameter('libelles', [3,7])
            ->getQuery()
            ->getResult();

    }
}
