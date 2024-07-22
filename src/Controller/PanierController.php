<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\PrixAAppliquer;
use App\Service\PanierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PanierController extends AbstractController
{
    #[Route('/panier/ajout/{codeprixApplique}', name: 'app_panier_ajout')]
    public function add(PrixAAppliquer $prixAAppliquer, PanierService $panierService, Request $request): Response
    {

        $session = $request->getSession();
        if ($session->get('chb_nb_jour')) {
            $nbJour = $session->get('chb_nb_jour');
            $panierService->ajout($prixAAppliquer, $nbJour);
        } else {
            $panierService->ajout($prixAAppliquer);
        }


        $this->addFlash(
            'success',
            "Produit correctement ajouté à votre panier."
        );
        //  flash()->success('Your contact has been removed.');
//       return $this->redirect($request->headers->get('referer'));
        return $this->redirectToRoute('app_order_summary');
    }

    #[Route('/panier/diminuer/{codeprixApplique}', name: 'app_panier_diminuer')]
    public function diminue(PrixAAppliquer $prixAAppliquer, PanierService $panierService, Request $request): Response
    {

        $session = $request->getSession();
        if ($session->get('chb_nb_jour')) {
            $nbJour = $session->get('chb_nb_jour');
            $panierService->ajout($prixAAppliquer, $nbJour);
        } else {
            $panierService->ajout($prixAAppliquer);
        }


        $this->addFlash(
            'success',
            "Produit correctement ajouté à votre panier."
        );
        //  flash()->success('Your contact has been removed.');
//       return $this->redirect($request->headers->get('referer'));
        return $this->redirectToRoute('app_order_summary');
    }

    #[Route('/test-reservation', name: 'app_panier_ajout1')]
    public function reservationTest()
    {
        return $this->render('front/commande/details_reservation.html.twig', [
//            'elements' => $panier,
//            'total' => $panierService->getTotalWt(),
        ]);
    }

    /**
     * @Route("/add-cart/{id}", name="add_cart")
     */
    public function add_cart(Produit $produit, PanierService $panier, SessionInterface  $session,Request $request,ProduitRepository $produitRepository)
    {
        $panier->ajout($produit->getId());
        $panier = $session->get('panier', []);
        $panierdata = [];
        foreach ($panier as $id => $quantity) {
            $panierdata[] = [
                'produit' => $produitRepository->find($id),
                'quantite' => $quantity
            ];
        }

        $totalProduit = count($panierdata);

        $total = 0;
        foreach ($panierdata as $item) {
            $subtotal = (int) $item['produit']->getPrix() * $item['quantite'];
            $total += $subtotal;
        }
        $totalProduit = count($panierdata);
        $total = array_reduce($panierdata, function ($carry, $item) {
            return $carry + $item['produit']->getPrix() * $item['quantite'];
        }, 0);

//        return new JsonResponse([
//            'totalPanier' => $total,
//            'totalProduit' => $totalProduit,
//            'elements' => $panierdata,
//        ]);
        $this->addFlash(
            'success',
            "Produit correctement ajouté à votre panier."
        );
        return new JsonResponse([
            'totalPanier' => $total,
            'totalProduit' => $totalProduit,
//            'elements' => $panierdata,
        ]);
    }



    #[Route('/cart/add/{codeprixApplique}', name: 'app_panier_ajout_ajax')]
    public function addAjax(PrixAAppliquer $prixAAppliquer, PanierService $panierService, Request $request): Response
    {

        $session = $request->getSession();
        if ($session->get('chb_nb_jour')) {
            $nbJour = $session->get('chb_nb_jour');
            $panierService->ajout($prixAAppliquer, $nbJour);
        } else {
            $panierService->ajout($prixAAppliquer);
        }


        $this->addFlash(
            'success',
            "Produit correctement ajouté à votre panier."
        );
        //  flash()->success('Your contact has been removed.');
//       return $this->redirect($request->headers->get('referer'));
        return new JsonResponse([
            'total' => $panierService->getTotalWt(),
            'status' => 'success',
            'message' => 'Produit ajouté au panier!',
//            'productId' => $panierService->,
//            'quantity' => $quantity,
//            'elements' => $panierdata,
        ]);
    }

}
