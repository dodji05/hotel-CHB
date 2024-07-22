<?php

namespace App\Repository;

use App\Entity\Facture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Facture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Facture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Facture[] findAll()
 * @method Facture[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FactureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Facture::class);
    }

    public function venteParPeriode($paramCodeFamille_pr,$paramDateFacture,$paramDateFacture1)
    {
        $rsm = new ResultSetMapping();
        $query = $this->getEntityManager()->createNativeQuery(
            "SELECT 
                        LigneFac.reffacture AS reffacture,	
                        LigneFac.LibProd AS LibProd,	
                        LigneFac.PrixVente AS PrixVente,	
                        LigneFac.Quantite AS Quantite,	
                        LigneFac.montantLigne AS montantLigne,	
                        facture.DateFacture AS DateFacture,	
                        LigneFac.Codeprix_applique AS Codeprix_applique,	
                        LigneFac.CodeFamille AS CodeFamille,	
                        Prix_A_Appliquer.Codeprix_applique AS codeprix_applique_Pr,	
                        Prix_A_Appliquer.codeservices AS codeservices,	
                        services.codeservices AS codeservices_se,	
                        services.Libe AS Libe,	
                        Prix_A_Appliquer.ID AS ID,	
                        produit.proFamille AS proFamille,	
                        produit.CodeFamille AS CodeFamille_pr
                        
                FROM 
                        services,	
                        Prix_A_Appliquer,	
                        produit,	
                        LigneFac,	
                        facture
                WHERE 
                        facture.reffacture = LigneFac.reffacture
                        AND		Prix_A_Appliquer.Codeprix_applique = LigneFac.Codeprix_applique
                        AND		produit.ID = Prix_A_Appliquer.ID
                        AND		services.codeservices = Prix_A_Appliquer.codeservices
                AND
                    (
                        facture.DateFacture <> ''
                        AND	produit.CodeFamille = {ParamCodeFamille_pr}
                        AND	facture.DateFacture BETWEEN {ParamDateFacture} AND {ParamDateFacture1}
                    )
                ORDER BY 
                        Libe ASC,	
                        proFamille ASC,	
                        DateFacture DESC
                        ",

            $rsm);

        $query->setParameter(1, $paramCodeFamille_pr);
        $query->setParameter(2, $paramDateFacture1);
        $query->setParameter(3, $paramCodeServices);
        return $query->getResult();
    }
}
