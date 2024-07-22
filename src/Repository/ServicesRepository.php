<?php

namespace App\Repository;

use App\Entity\Services;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Services>
 *
 * @method Services|null find($id, $lockMode = null, $lockVersion = null)
 * @method Services|null findOneBy(array $criteria, array $orderBy = null)
 * @method Services[]    findAll()
 * @method Services[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServicesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Services::class);
    }

    //    /**
    //     * @return Services[] Returns an array of Services objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Services
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function chiffresAffairesParServices($paramDateFacture, $paramDateFacture1, $paramCodeServices)
    {
        $qb = $this->createQueryBuilder('s')
            ->select('s.codeService AS codeService', 's.libelle AS libe', 'SUM(lf.montantligne) AS la_somme_montantLigne')
            ->innerJoin('s.tables', 't')
            ->innerJoin('t.factures', 'f')
            ->innerJoin('f.ligneFacs', 'lf')
//            ->andWhere('f.datefacture BETWEEN :paramDateFacture AND :paramDateFacture1')
//            ->andWhere('s.codeService = :paramCodeServices')
//            ->setParameter('paramDateFacture', $paramDateFacture)
//            ->setParameter('paramDateFacture1', $paramDateFacture1)
//            ->setParameter('paramCodeServices', $paramCodeServices)
            ->groupBy('s.codeService', 's.libelle');

        return $qb->getQuery()->getResult();
    }

    public function ffff($paramDateFacture, $paramDateFacture1, $paramCodeServices)
    {
        $rsm = new ResultSetMapping();
        $query = $this->getEntityManager()->createNativeQuery(
            "SELECT DISTINCT 
                            services.codeservices AS codeservices,	
                            services.Libe AS Libe,	
                            SUM(LigneFac.montantLigne) AS la_somme_montantLigne
                 FROM 
                            services,	
                            tabless,	
                            facture,	
                            LigneFac
                           
                WHERE 
                            facture.reffacture = LigneFac.reffacture
                           AND		services.codeservices = tabless.codeservices
                            AND
                                (
                                    facture.DateFacture <> ''
                                    
                                )
                GROUP BY 
                            services.codeservices,	
                            services.Libe

            
            ",

            $rsm);
//        $query->setParameter(1, $paramDateFacture);
//        $query->setParameter(2, $paramDateFacture1);
//        $query->setParameter(3, $paramCodeServices);
        return $query->getQuery()->getResult();

    }

    public function CA()
    {
        return $this->createQueryBuilder('s')
//            ->select('s.codeService AS codeService', 's.libelle AS libe', 'SUM(lf.montantligne) AS la_somme_montantLigne')
            ->select('s.codeService AS codeService', 's.libelle AS libe', 'SUM(lf.quantite* lf.prixvente) AS la_somme_montantLigne')
            ->leftJoin('s.prixAAppliquers', 'pp')
            ->leftJoin('pp.ligneFacs', 'lf')
            ->groupBy('s.codeService', 's.libelle')
            ->orderBy('la_somme_montantLigne', 'DESC')
            ->getQuery()
            ->getResult();


    }

    public function CAParService()
    {
        return $this->createQueryBuilder('s')
//            ->select('s.codeService AS codeService', 's.libelle AS libe', 'SUM(lf.montantligne) AS la_somme_montantLigne')
            ->select('DISTINCT lf.idlignefac', 's.codeService AS codeService', 'pp.prix', 'pr.libprod','s.libelle AS libe', 'lf.quantite', 'lf.prixvente', 'fac.datefacture')
            ->innerJoin('s.prixAAppliquers', 'pp')
            ->innerjoin('pp.ID', 'pr')
//            ->innerJoin('pr','pr')
            ->innerJoin('pp.ligneFacs', 'lf')
            ->innerjoin('lf.reffacture', 'fac')
//            ->distinct(true)
            // ->groupBy('s.codeService')
            //  ->orderBy('la_somme_montantLigne','DESC')
            ->getQuery()
            ->getResult();


    }

    public function getServiceCa()
    {
        return $this->createQueryBuilder('s')
            ->select('s.codeService AS codeService, s.libelle AS libe')
            ->innerJoin('s.prixAAppliquers', 'pp')
            ->innerJoin('pp.ligneFacs', 'lf')
            ->innerjoin('lf.reffacture', 'fac')
            ->groupBy('s.codeService')
            ->getQuery()

            ->getResult();
    }

    public function getClientParService()
    {
        return $this->createQueryBuilder('s')
//            ->select('s.codeService AS codeService, s.libelle AS libe,COUNT(fac.NumClient) AS nb')
            ->select('COUNT  ( DISTINCT fac.NumClient) AS nb,s.codeService AS codeService, s.libelle AS libe')
            ->innerJoin('s.prixAAppliquers', 'pp')
            ->innerJoin('pp.ligneFacs', 'lf')
            ->innerjoin('lf.reffacture', 'fac')
            ->innerJoin('fac.NumClient','c')
            ->andWhere('fac.datefacture != \'\'')
            ->groupBy('fac.NumClient,s.codeService  ')
            ->getQuery()

            ->getResult();
    }

    public function CAParServicePeriode($service,$dateDebut=null,$dateFin=null)
    {
        return $this->createQueryBuilder('s')
//            ->select('s.codeService AS codeService', 's.libelle AS libe', 'SUM(lf.montantligne) AS la_somme_montantLigne')
            ->select('DISTINCT lf.idlignefac', 's.codeService AS codeService', 'pp.prix', 'pr.libprod', 's.libelle AS libe', 'lf.quantite', 'lf.prixvente', 'fac.datefacture')
            ->innerJoin('s.prixAAppliquers', 'pp')
            ->innerjoin('pp.ID', 'pr')
//            ->innerJoin('pr','pr')
            ->innerJoin('pp.ligneFacs', 'lf')
            ->leftjoin('lf.reffacture', 'fac')
            ->andWhere('s.codeService = :service')
            ->andWhere('fac.datefacture BETWEEN :dateDebut AND :dateFin')
            ->setParameter('service', $service)
            ->setParameter('dateDebut', $dateDebut)
            ->setParameter('dateFin', $dateFin)
//            ->distinct(true)
            // ->groupBy('s.codeService')
            //  ->orderBy('la_somme_montantLigne','DESC')
            ->getQuery()

            ->getResult()
        ;


    }
}
