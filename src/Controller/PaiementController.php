<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Facture;
use App\Repository\FactureRepository;
use App\Repository\LigneFacRepository;
use App\Repository\LigneFactureRepository;
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
        $public_key = "bab3b920653411ef8a1de375275411b5";
        $private_key = "tpk_bab3e030653411ef8a1de375275411b5";
        $secret = "tsk_bab3e031653411ef8a1de375275411b5";
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
                $panier = $session->get('chb_panier', []);
                $total = $panierService->getTotalWt();

                $client = new Client();
                $client->setNom($cartClient['nom']);
                if ($cartClient['civilite'] == 'Monsieur') {
                    $client->setSexe('Masculin');
                } else {
                    $client->setSexe('Feminin');
                }

                $client->setAdresse($cartClient['adresse']);
                $client->setObservation($cartClient['observation']);
                $client->setTelephone($cartClient['telephone']);
                $numclient = $identifierGenerator->generateUniqueIdentifier(Client::class, 'referenceSysteme', 'CL');
                $client->setReferenceManuel($numclient);
                $client->setReferenceSysteme($numclient);

                $entityManager->persist($client);

                $facture = $factureRepository->find($idFacture);
//                dd($idFacture);
//                $facture->setNumfacture($numfacture);
                //   $facture->setNumfacture('FFFF');
                $numFacture = $identifierGenerator->generateUniqueIdentifier(Facture::class, 'referenceSysteme', 'E-FAC');

                $facture->setClient($client);
                $facture->setReferenceManuel($numFacture);
                $facture->setReferenceSysteme($numFacture);
                $facture->setNormalisee(false);
//                $facture->setAcquittee(true);
                $facture->setDatefacture(new \DateTime());
                $facture->setTotalht($total);
                $facture->setEstPaye(true);
//                $facture->setNomclient($cartClient['nom']);
//                $facture->setIdmodereglement('90');
                $facture->setEstCommande(true);

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
    public function facture( LigneFactureRepository
    $ligneFactureRepository,
                            PanierService $panierService, RequestStack $requestStack)
    {
        $session = $requestStack->getSession();
//        $facture = $session->get('chb_panier_facture', []);
        $cartClient = $session->get('chb_panier_client');
//        $panier = $session->get('chb_panier', []);
//        $total = $panierService->getTotalWt();

        $idFacture = $session->get('chb_panier_order', []);
        $ligneFacture = $ligneFactureRepository->findBy(['facture' => $idFacture]);
//        dd($ligneFacture);

        $total = 0;
        foreach ($ligneFacture as $ligne) {
            $panier[(string)$ligne->getProduit()->getId()] = [
                'object' => $ligne->getProduit(),
                'quantite' => $ligne->getQuantite(),
                'produit' => $ligne->getLibelleProduit()
            ];

            $subtotal = (int)$ligne->getProduit()->getPrix() * $ligne->getQuantite();
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
            'etat' => 2,
            'numfacture' => $idFacture,
            'client' => $cartClient,
            'status' => 'Payée',
            'message' => 'Commande payée avec succes'

        ]);

    }
}
