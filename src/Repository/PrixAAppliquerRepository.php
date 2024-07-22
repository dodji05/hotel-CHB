<?php

namespace App\Repository;

use App\Entity\PrixAAppliquer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PrixAAppliquer|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrixAAppliquer|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrixAAppliquer[] findAll()
 * @method PrixAAppliquer[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrixAAppliquerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PrixAAppliquer::class);
    }

    public function findProduitByService($service): array
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.ID', 'produit')
            ->innerJoin('p.codeservice', 'codeservice')
            ->andWhere('codeservice.codeService = :seruce')
            ->setParameter('seruce', $service)
            ->getQuery()
            ->getResult();
    }

    public function findProduitFamilleByService($service, $admin=false ,$limit = null): array
    {

        $qb = $this->createQueryBuilder('p')
            ->innerJoin('p.ID', 'produit')
            ->innerJoin('produit.CodeFamille', 'codefamille')
            ->innerJoin('p.codeservice', 'codeservice')
            ->andWhere('codeservice.codeService = :seruce');
       //     ->andWhere('produit.photo IS NOT NULL')
            if (!$admin) {
                $qb->andWhere('produit.photo != \'\'');
            }
            ;
           $qb->setParameter('seruce', $service);
        if ($limit != null) {
            $qb->setMaxResults($limit);
        };

        return   $qb->getQuery()
            ->getResult();

//        return $qb;
    }

    public function findProduitFamilleByServiceHebergement($service, $famille,$admin=false ,$limit = null): array
    {

        $qb = $this->createQueryBuilder('p')
            ->innerJoin('p.ID', 'produit')
            ->innerJoin('produit.CodeFamille', 'codefamille')
            ->innerJoin('p.codeservice', 'codeservice')
            ->andWhere('codeservice.codeService = :seruce')
            ->andWhere('codefamille.codeFamille = :famille');
        //     ->andWhere('produit.photo IS NOT NULL')
        ;
        if (!$admin) {
            $qb->andWhere('produit.photo != \'\'');
        }
        ;
        $qb->setParameter('seruce', $service);
        $qb->setParameter('famille', $famille);
        if ($limit != null) {
            $qb->setMaxResults($limit);
        };

        return   $qb->getQuery()
            ->getResult();

//        return $qb;
    }
    public function findAvailableRooms(\DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.ligneFacs', 'res')
            ->andwhere('p.codeservice = :codeservice')
            ->andwhere('res.dateentree > :endDate OR res.datesortie < :startDate OR res.idlignefac IS NULL')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('codeservice', 'S0000011')
            ->getQuery()
            ->getResult();

        return $qb;
    }
}
