<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Client>
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[] findAll()
 * @method Client[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function monbreClient(): int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.numclient) as nbClient')
            ->getQuery()
            ->getSingleScalarResult();;
    }

    public function meilleursClient($paramDateFacture, $paramDateFacture1)
    {
        $rsm = new ResultSetMapping();
        $query = $this->getEntityManager()->createNativeQuery(
            "SELECT 
                       SUM(facture.NetApayer) AS la_somme_NetApayer,	
	                    Client.NomClient +'' +Client.Prenom AS NomClient
                        
                FROM 
                        Client,	
	                    facture
                WHERE 
                      Client.NumClient = facture.NumClient
                AND
                    (
                        facture.DateFacture BETWEEN ?1 AND ?2
		                AND	facture.DateFacture <> ''
                    )
              GROUP BY 
	                Client.NomClient +'' +Client.Prenom
                    ORDER BY 
	                la_somme_NetApayer ASC
                        ",

            $rsm);

        $query->setParameter(1, $paramDateFacture);
        $query->setParameter(2, $paramDateFacture1);

        return $query->getResult();
    }

    public function topClient($paramDateFacture = null, $paramDateFacture1 = null)
    {
//        CONCAT(Prénom, ’ ’, Nomfamille)

            $qb = $this->createQueryBuilder('c')
                ->select('SUM(facture.netapayer) AS la_somme_NetApayer,c.nomclient,')
                ->innerJoin('c.factures', 'facture');
//            ->groupBy('c.prenom')
                if ($paramDateFacture != null and $paramDateFacture != null) {
                    $qb->andWhere('facture.datefacture BETWEEN :debut AND :fin')
                        ->setParameter('debut', $paramDateFacture)
                        ->setParameter('fin', $paramDateFacture1);
                }
        return $qb->andWhere('facture.datefacture !=0')
             ->groupBy('c.nomclient')
             ->getQuery()
             ->getResult();

    }

    public function topClientParServices()
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(facture.NumClient) AS nbClient')
            ->Join('c.factures', 'facture')
            ->Join('facture.ligneFacs', 'ligneFacs')
            ->andWhere('facture.datefacture !=0')
            ->groupBy('facture.NumClient')
            ->addGroupBy('ligneFacs.reffacture')
            ->getQuery()
            ->getSQL()//    getResult()
            ;

    }
}
