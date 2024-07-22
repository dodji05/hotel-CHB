<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Facture;
use App\Repository\FactureRepository;
use App\Repository\LigneFacRepository;
use App\Service\PanierService;
use App\Service\UniqueIdentifierGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Kkiapay\Kkiapay;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PaiementController extends AbstractController
{
    #[Route('/paiement', name: 'app_cart_checkout_kkipay')]
    public function index(RequestStack $requestStack, PanierService $panierService, UniqueIdentifierGenerator $identifierGenerator, FactureRepository $factureRepository, EntityManagerInterface $entityManager): Response
    {
//        $public_key = "92d0cae00f8711eaa1d639db8721ba5b";
//        $private_key = "pk_92d0cae20f8711eaa1d639db8721ba5b";
//        $secret = "sk_92d0f1f00f8711eaa1d639db8721ba5b";

        $request = $requestStack->getMainRequest();
        $public_key = "59ebf8906c9011ec9f5205a1aca9c042";
        $private_key = "tpk_59ebf8926c9011ec9f5205a1aca9c042";
        $secret = "tsk_59ebf8936c9011ec9f5205a1aca9c042";
        $numfacture = '';
     //   $transaction_id = 'UIAK3n40F';
          $transaction_id = $request->get('transaction_id');

        if ($transaction_id) {
            $kkiapay = new Kkiapay($public_key, $private_key, $secret, true);

            $verify = $kkiapay->verifyTransaction($transaction_id);
            $status = $verify->status;
            if ($status == 'SUCCESS') {

                $session = $requestStack->getSession();
                $cartClient = $session->get('chb_panier_client');
                $idFacture = $session->get('chb_panier_order', []);
                $total = $panierService->getTotalWt();

                $client = new Client();
                $client->setNomclient($cartClient['nom']);
                $client->setCivilite(/*$cartClient['civilite']*/ 'Mr');
                $client->setAdresse($cartClient['adresse']);
                $client->setObservations($cartClient['observation']);
                $client->setTelephone($cartClient['telephone']);
                $numclient = $identifierGenerator->generateUniqueIdentifier(Client::class, 'numclient', 'CL');
                $client->setNumclient($numclient);

                $entityManager->persist($client);

                $facture = $factureRepository->findOneBy(['reffacture'=>$idFacture]);
//                dd($idFacture);
//                $facture->setNumfacture($numfacture);
                //   $facture->setNumfacture('FFFF');
                $numFacture = $identifierGenerator->generateUniqueIdentifier(Facture::class, 'numfacture', 'FAC');

                $facture->setNumClient($client);
                $facture->setNumFacture($numFacture);
                $facture->setNormalisee(false);
                $facture->setAcquittee(true);
                $facture->setDatefacture(new \DateTime());
                $facture->setTotalht((string)$total);
                $facture->setEstPaye(true);
                $facture->setNomclient($cartClient['nom']);
//                $facture->setIdmodereglement('90');
                $facture->setEstCommander(true);

                $entityManager->persist($facture);
                $entityManager->flush();
                //  dd($products['PA00000035']) ;
                /*               foreach ($products as $product) {
                                   $ligneFacture = new LigneFac();
                                   $idlignefac = $identifierGenerator->generateUniqueIdentifier(LigneFac::class, 'idlignefac', 'LF');
                                   $ligneFacture->setIdlignefac($idlignefac);
               //                    dd($facture, $idlignefac);
               //                    $ligneFacture->setReffacture($facture);
                                   $ligneFacture->setReffacture($facture);
                                   $ligneFacture->setCodeprixApplique($product['object']);
                                   $ligneFacture->setLibprod($product['produit']);
                                   $ligneFacture->setPuht($product['object']->getPrix());
                                   $ligneFacture->setPrixvente((string)$product['object']->getPrix());
                                   $ligneFacture->setQuantite($product['quantite']);

                                   $entityManager->persist($ligneFacture);
                                   $entityManager->flush();
                                   $idlignefac = $identifierGenerator->generateUniqueIdentifier(LigneFac::class, 'idlignefac', 'LF');

                               }
               */
                $session->set('chb_panier_facture', $facture);
            }


//
//
        }


        return $this->redirectToRoute('app_cart_checkout_facture');
    }

    #[Route('/paiement/facture', name: 'app_cart_checkout_facture')]
    public function facture(FactureRepository $factureRepository,LigneFacRepository $ligneFacRepository,PanierService $panierService ,RequestStack $requestStack)
    {
        $session = $requestStack->getSession();
//        $facture = $session->get('chb_panier_facture', []);
        $cartClient = $session->get('chb_panier_client');
//        $panier = $session->get('chb_panier', []);
//        $total = $panierService->getTotalWt();

        $idFacture = $session->get('chb_panier_order', []);
        $ligneFacture = $ligneFacRepository->findBy(['reffacture' => $idFacture]);

        $total = 0;
        foreach ($ligneFacture as $ligne)
        {
            $panier[$ligne->getCodeprixApplique()->getCodeprixApplique()] = [
                'object' => $ligne->getCodeprixApplique(),
                'quantite' => $ligne->getQuantite(),
                'produit' => $ligne->getCodeprixApplique()->getID()->getLibprod()
            ];

            $subtotal = (int)$ligne->getCodeprixApplique()->getPrix() * $ligne->getQuantite();
            $total += $subtotal;
        }

        $session->remove('chb_panier_client');
        $session->remove('chb_reservation_periode');
//        $session->remove('chb_panier_order');
        $session->remove('chb_panier');
        $session->remove('chb_nb_jour');
        $session->remove('chb_panier_facture');


//        $session->remove('chb_nb_jour');
//        $session->remove('chb_panier');
//        $session->remove('chb_panier_facture');
//        $session->remove('chb_panier_order');
//        $session->remove('chb_reservation_periode');

        return $this->render('front/commande/prefacture.html.twig', [
            'elements' => $panier,
            'total' => $total,
            'etat'=>2,
            'numfacture' =>  $idFacture,
            'client'=> $cartClient,
            'status'=>'Payée',
            'message'=>'Commande payée avec succes'

        ]);

    }
}
