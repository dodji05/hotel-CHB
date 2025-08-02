<?php

namespace App\Controller\Admin;

use App\Repository\ClientRepository;
use App\Repository\ProduitRepository;
use App\Repository\ServicesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

#[Route('administration')]
class TBController extends AbstractController
{
    #[Route('/', name: 'admin_TB_index', methods: ['GET'])]
    public function index(ClientRepository $clientRepository,ServicesRepository $servicesRepository,ProduitRepository $produitRepository)
    {
        $titre = 'Tableau de bord';
        $sousTitre1 ='Accueil';
        $sousTitre2 ='Tableau de bord';
        $nbClient  = $clientRepository->monbreClient();
        $chiffresAffaires = $servicesRepository->CA();
        $totalCA = 0;
        foreach ($chiffresAffaires as $chiffresAffaire) {
            $totalCA += $chiffresAffaire['la_somme_montantLigne'];

        }
        $servicesClientParServices = array();
        $valeurClientParServices = array();
        $nbClientParService = $servicesRepository->getClientParService();
        foreach ( $nbClientParService as $key => $valeur) {
            $servicesClientParServices[] = $valeur["libe"];
            $valeurClientParServices[] = $valeur["nb"];
        }
//        dd( $p, $v );
     //   $produitEnstockDeReapprovisionnement = $produitRepository->produitEnstockDeReapprovisionnement();
        $produitEnstockDeReapprovisionnement = 0;

        $produitEnstockAlerte = 0;
       // $produitEnstockAlerte = $produitRepository->produitEnstockAlerte();




        return $this->render('admin/tableau_bord.html.twig', [
//            'familles' => $familleRepository->findAll(),
        'nbClient' => $nbClient,
            'chiffresAffaires'=>$chiffresAffaires,
            'CA'=>$totalCA,
            'produitEnstockDeReapprovisionnement'=>$produitEnstockDeReapprovisionnement,
            'produitEnstockAlerte'=> $produitEnstockAlerte,
           'servicesClientParServices'=> json_encode( $servicesClientParServices),
           'valeurClientParServices'=> json_encode( $valeurClientParServices),

        ]);

    }


}
