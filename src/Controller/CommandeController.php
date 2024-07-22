<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Service\PanierService;
use App\Service\UniqueIdentifierGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CommandeController extends AbstractController
{
    #[Route('/commande/recapitulatif', name: 'app_order_summary')]
    public function index(RequestStack $requestStack,PanierService $panierService): Response
    {
        $session = $requestStack->getSession();
        $panier = $session->get('chb_panier', []);
        $typepanier = $session->get('chb_panier_type');

        $panierdata = [];

        $total = 0;
        foreach ($panierdata as $item) {
            $subtotal = (int)$item['produit']->getPrix() * $item['quantite'];
            $total += $subtotal;

        }
        return $this->render('front/commande/index.html.twig', [
            'elements' => $panier,
            'total' => $panierService->getTotalWt(),
            'typepanier'=>$typepanier
        ]);
    }

    #[Route('/commande/check-out', name: 'app_order_check_out')]
    public function checkout(RequestStack $requestStack, PanierService $panierService, UniqueIdentifierGenerator $identifierGenerator)
    {
        $session = $requestStack->getSession();
        $typepanier = $session->get('chb_panier_type');
        $cartClient = $session->get('chb_panier_client');
        if ($cartClient) {
            $session->remove('chb_panier_client');
        }

        $client = new Client();
//        $numclient = $identifierGenerator->generateUniqueIdentifier(Client::class, 'numclient', 'CL');
//        $client->setNumclient($numclient);

        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($requestStack->getMainRequest());

        $clientInfos = [];


        if ($form->isSubmitted() && $form->isValid()) {
//            $form->getData('')->getData();
            $clientInfos = [
                'civilite' => $form->get('civilite')->getData(),
                'nom' => $form->get('nomclient')->getData(),
                'telephone' => $form->get('telephone')->getData(),
                'observation' => $form->get('observations')->getData(),
                'optionLivraison' => $form->get('optionLivraison')->getData(),
                'adresse' => $form->get('adresse')->getData(),
            ];

            $session->set('chb_panier_client', $clientInfos);

            return $this->redirectToRoute('app_order_pay');
        }


        $panier = $session->get('chb_panier', []);
        $total = $panierService->getTotalWt();

        return $this->render('front/commande/commande_validation.html.twig', [
            'elements' => $panier,
            'total' => $total,
            'form' => $form->createView(),
            'typepanier'=>$typepanier
        ]);
    }
    #[Route('/commande/paiement', name: 'app_order_pay')]
    public function resume(RequestStack $requestStack, PanierService $panierService)
    {
        $session = $requestStack->getSession();

        $cartClient = $session->get('chb_panier_client');
        $panier = $session->get('chb_panier', []);
        $total = $panierService->getTotalWt();

        return $this->render('front/commande/prefacture.html.twig', [
            'elements' => $panier,
            'total' => $total,
            'client'=> $cartClient,
            'status'=>'Paiement en attente',
            'message'=>'Pour valider votre commande vous devez proceder aux paiement'

        ]);
    }
}
