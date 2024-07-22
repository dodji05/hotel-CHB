<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\LigneFac;
use App\Entity\PrixAAppliquer;
use App\Repository\FactureRepository;
use App\Repository\LigneFacRepository;
use App\Repository\PrixAAppliquerRepository;
use App\Service\PanierService;
use App\Service\UniqueIdentifierGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{
    #[Route('/order/cart/add/{codeprixApplique}', name: 'app_order_cart_add')]
    public function index(PrixAAppliquer $prixAAppliquer, UniqueIdentifierGenerator $identifierGenerator, RequestStack $requestStack, FactureRepository $factureRepository, PanierService $panierService, EntityManagerInterface $entityManager, LigneFacRepository $ligneFacRepository): Response
    {
        $session = $requestStack->getSession();
        $idFacture = $session->get('chb_panier_order', []);
//        dd(empty( $idFacture),isset( $idFacture), $idFacture);

        if (empty($idFacture)) {
            $chpanier = $session->get('chb_panier', []);
            if ($chpanier) {
                $session->remove('chb_panier');
            }
            $facture = new Facture();
            $reffacture = $identifierGenerator->generateUniqueIdentifier(Facture::class, 'reffacture', 'RFA');
            $facture->setDatefacture(new \DateTime());
            $facture->setReffacture($reffacture);
            $facture->setNormalisee(false);
            $facture->setAcquittee(false);
            $facture->setEstCommander(true);
//            $facture->setIdmodereglement('90');
            $facture->setStatut('EN LIGNE');
            $entityManager->persist($facture);


            $ligneFacture = new LigneFac();
            $idlignefac = $identifierGenerator->generateUniqueIdentifier(LigneFac::class, 'idlignefac', 'LF');
            $ligneFacture->setIdlignefac($idlignefac);
            $ligneFacture->setReffacture($facture);
            $ligneFacture->setCodeprixApplique($prixAAppliquer);
            $ligneFacture->setLibprod($prixAAppliquer->getID()->getLibprod());
            $ligneFacture->setPuht($prixAAppliquer->getPrix());
            $ligneFacture->setPrixvente((string)$prixAAppliquer->getPrix());
            if ($session->get('chb_nb_jour')) {
                $nbJour = $session->get('chb_reservation_periode');
                $info_reservation = $session->get('chb_reservation_periode');
                $ligneFacture->setQuantite($info_reservation['nbJours']);
                $ligneFacture->setDateentree($info_reservation['dateDebut']);
                $ligneFacture->setDateSortie($info_reservation['dateFin']);
                $ligneFacture->setNbrejoure($info_reservation['nbJours']);

            } else {
                $ligneFacture->setQuantite(1);
            }

            $entityManager->persist($ligneFacture);
            $entityManager->flush();
            $session->set('chb_panier_order', $facture->getReffacture());


            if ($session->get('chb_reservation_periode')) {
                $info_reservation = $session->get('chb_reservation_periode');
                $panierService->ajout($prixAAppliquer, $info_reservation['nbJours']);
            } else {
                $panierService->ajout($prixAAppliquer);
            }
//            $panierService->ajout($prixAAppliquer);

        } else {
            $facture = $factureRepository->find($idFacture);
            if ($facture) {
                //On verifie sur le produit est deja dans le panier
                $ligneFacture = $ligneFacRepository->findOneBy(['reffacture' => $idFacture, 'codeprixApplique' => $prixAAppliquer]);
                $quantite = 1;
                if ($ligneFacture) {
                    // $ligneFacture = $produitDeja;
                    $quantite = $ligneFacture->getQuantite() + 1;

                    $ligneFacture->setQuantite($quantite);
                } else {
                    $ligneFacture = new LigneFac();
                    $idlignefac = $identifierGenerator->generateUniqueIdentifier(LigneFac::class, 'idlignefac', 'LF');
                    $ligneFacture->setIdlignefac($idlignefac);
                }

//                    dd($facture, $idlignefac);
//                    $ligneFacture->setReffacture($facture);

                $ligneFacture->setReffacture($facture);
                $ligneFacture->setCodeprixApplique($prixAAppliquer);
                $ligneFacture->setLibprod($prixAAppliquer->getID()->getLibprod());
                $ligneFacture->setPuht($prixAAppliquer->getPrix());
                $ligneFacture->setPrixvente((string)$prixAAppliquer->getPrix());
                if ($session->get('chb_reservation_periode')) {
                    $info_reservation = $session->get('chb_reservation_periode');
                    // $panierService->ajout($prixAAppliquer,$info_reservation['nbJours']);

                    // $nbJour = $session->get('chb_nb_jour');
                    $ligneFacture->setQuantite($info_reservation['nbJours']);
                    $ligneFacture->setDateentree($info_reservation['dateDebut']);
                    $ligneFacture->setDateSortie($info_reservation['dateFin']);

                } else {
                    $ligneFacture->setQuantite($quantite);
                }

                $entityManager->persist($ligneFacture);
                $entityManager->flush();
                $session->set('chb_panier_order', $facture->getReffacture());

                if ($session->get('chb_reservation_periode')) {
                    //   $nbJour = $session->get('chb_nb_jour');
                    $info_reservation = $session->get('chb_reservation_periode');
                    $panierService->ajout($prixAAppliquer, $info_reservation['nbJours']);
                } else {
                    $panierService->ajout($prixAAppliquer);
                }

            }
        }

        return $this->redirectToRoute('app_order_summary');
    }

    #[Route('/order/cart/decrease/{codeprixApplique}', name: 'app_order_cart_decrease')]
    public function decrease(PrixAAppliquer $prixAAppliquer, RequestStack $requestStack, PanierService $panierService, EntityManagerInterface $entityManager, LigneFacRepository $ligneFacRepository)
    {
        $session = $requestStack->getSession();
        $idFacture = $session->get('chb_panier_order', []);
        if (!empty($idFacture)) {
            $qte = 0;
            $chpanier = $session->get('chb_panier', []);
            $produit = $ligneFacRepository->findOneBy(['codeprixApplique' => $prixAAppliquer, 'reffacture' => $idFacture]);
            if ($produit) {
                if ($produit->getQuantite() > 1) {
                    $qte = $produit->getQuantite() - 1;
                    $produit->setQuantite($qte);
                    $entityManager->persist($produit);
                } else {
                    $entityManager->remove($produit);
                }
                $entityManager->flush();
                $panierService->dimunier($prixAAppliquer);

            }
        }
        return $this->redirectToRoute('app_order_summary');
    }

    #[Route('/order/cart/supprimer/{codeprixApplique}', name: 'app_order_cart_supprimer')]
    public function supprimer(PrixAAppliquer $prixAAppliquer, RequestStack $requestStack, PanierService $panierService, EntityManagerInterface $entityManager, LigneFacRepository $ligneFacRepository, FactureRepository $factureRepository)
    {
        $session = $requestStack->getSession();
        $idFacture = $session->get('chb_panier_order', []);
        if (!empty($idFacture)) {
            $produit = $ligneFacRepository->findOneBy(['codeprixApplique' => $prixAAppliquer, 'reffacture' => $idFacture]);
            if ($produit) {
                $entityManager->remove($produit);
                $entityManager->flush();
                $panierService->supprimer($prixAAppliquer);

            }
        }
        $commade = $ligneFacRepository->findBy(['reffacture' => $idFacture]);
        // dd( empty($commade),$idFacture);
        if (empty($commade)) {
            $facture = $factureRepository->findOneBy(['reffacture' => $idFacture]);
            if ($facture) {
                $entityManager->remove($facture);
                $entityManager->flush();

                $session->remove('chb_nb_jour');
                $session->remove('chb_panier');
                $session->remove('chb_panier_facture');
                $session->remove('chb_panier_order');
                $session->remove('chb_reservation_periode');
                $session->remove('chb_panier_type');
            }

        }
        return $this->redirectToRoute('app_order_summary');
    }


    #[Route('/order/cart/ajax/add/{codeprixApplique}', name: 'app_order_cart_add_ajax')]
    public function addAjax(Request $request,PrixAAppliquer $prixAAppliquer,PrixAAppliquerRepository $prixAAppliquerRepository, UniqueIdentifierGenerator $identifierGenerator, RequestStack $requestStack, FactureRepository $factureRepository, PanierService $panierService, EntityManagerInterface $entityManager, LigneFacRepository $ligneFacRepository): Response
    {

//        $prixAAppliquer = $prixAAppliquerRepository->findOneBy(['codeprixApplique' => 'PA0000008']);
        $session = $requestStack->getSession();
        $idFacture = $session->get('chb_panier_order', []);
//        dd(empty( $idFacture),isset( $idFacture), $idFacture);
//        $chpanier = $session->get('chb_panier', []);
        if (empty($idFacture)) {
            $chpanier = $session->get('chb_panier', []);
            if ($chpanier) {
                $session->remove('chb_panier');
            }
            $facture = new Facture();
            $reffacture = $identifierGenerator->generateUniqueIdentifier(Facture::class, 'reffacture', 'RFA');
            $facture->setDatefacture(new \DateTime());
            $facture->setReffacture($reffacture);
            $facture->setNormalisee(false);
            $facture->setAcquittee(false);
            $facture->setEstCommander(true);
//            $facture->setIdmodereglement('90');
            $facture->setStatut('EN LIGNE');
            $entityManager->persist($facture);


            $ligneFacture = new LigneFac();
            $idlignefac = $identifierGenerator->generateUniqueIdentifier(LigneFac::class, 'idlignefac', 'LF');
            $ligneFacture->setIdlignefac($idlignefac);
            $ligneFacture->setReffacture($facture);
            $ligneFacture->setCodeprixApplique($prixAAppliquer);
            $ligneFacture->setLibprod($prixAAppliquer->getID()->getLibprod());
            $ligneFacture->setPuht($prixAAppliquer->getPrix());
            $ligneFacture->setPrixvente((string)$prixAAppliquer->getPrix());
            if ($session->get('chb_nb_jour')) {
                $nbJour = $session->get('chb_reservation_periode');
                $info_reservation = $session->get('chb_reservation_periode');
                $ligneFacture->setQuantite($info_reservation['nbJours']);
                $ligneFacture->setDateentree($info_reservation['dateDebut']);
                $ligneFacture->setDateSortie($info_reservation['dateFin']);
                $ligneFacture->setNbrejoure($info_reservation['nbJours']);

            } else {
                $ligneFacture->setQuantite(1);
            }

            $entityManager->persist($ligneFacture);
            $entityManager->flush();
            $session->set('chb_panier_order', $facture->getReffacture());


            if ($session->get('chb_reservation_periode')) {
                $info_reservation = $session->get('chb_reservation_periode');
                $panierService->ajout($prixAAppliquer, $info_reservation['nbJours']);
            } else {
                $panierService->ajout($prixAAppliquer);
            }
//            $panierService->ajout($prixAAppliquer);

        } else {
            $facture = $factureRepository->find($idFacture);
            if ($facture) {
                //On verifie sur le produit est deja dans le panier
                $ligneFacture = $ligneFacRepository->findOneBy(['reffacture' => $idFacture, 'codeprixApplique' => $prixAAppliquer]);
                $quantite = 1;
                if ($ligneFacture) {
                    // $ligneFacture = $produitDeja;
                    $quantite = $ligneFacture->getQuantite() + 1;

                    $ligneFacture->setQuantite($quantite);
                } else {
                    $ligneFacture = new LigneFac();
                    $idlignefac = $identifierGenerator->generateUniqueIdentifier(LigneFac::class, 'idlignefac', 'LF');
                    $ligneFacture->setIdlignefac($idlignefac);
                }

//                    dd($facture, $idlignefac);
//                    $ligneFacture->setReffacture($facture);

                $ligneFacture->setReffacture($facture);
                $ligneFacture->setCodeprixApplique($prixAAppliquer);
                $ligneFacture->setLibprod($prixAAppliquer->getID()->getLibprod());
                $ligneFacture->setPuht($prixAAppliquer->getPrix());
                $ligneFacture->setPrixvente((string)$prixAAppliquer->getPrix());
                if ($session->get('chb_reservation_periode')) {
                    $info_reservation = $session->get('chb_reservation_periode');
                    // $panierService->ajout($prixAAppliquer,$info_reservation['nbJours']);

                    // $nbJour = $session->get('chb_nb_jour');
                    $ligneFacture->setQuantite($info_reservation['nbJours']);
                    $ligneFacture->setDateentree($info_reservation['dateDebut']);
                    $ligneFacture->setDateSortie($info_reservation['dateFin']);

                } else {
                    $ligneFacture->setQuantite($quantite);
                }

                $entityManager->persist($ligneFacture);
                $entityManager->flush();
                $session->set('chb_panier_order', $facture->getReffacture());

                if ($session->get('chb_reservation_periode')) {
                    //   $nbJour = $session->get('chb_nb_jour');
                    $info_reservation = $session->get('chb_reservation_periode');
                    $panierService->ajout($prixAAppliquer, $info_reservation['nbJours']);
                } else {
                    $panierService->ajout($prixAAppliquer);
                }

            }
        }
        $chpaniers = $session->get('chb_panier', []);
        return new JsonResponse([
            'total' => $panierService->getTotalWt(),
            'status' => 'success',
            'message' => 'Produit ajouté au panier!',
          //  'productId' => $prixAAppliquer->getCodeprixApplique() ,
         //   'prix' => $prixAAppliquer->getPrix() ,
//            'quantity' => $chpanier[$prixAAppliquer->getCodeprixApplique()]['quantite'],
//            'quantity' => 1,
            'cart' => $chpaniers,
        ]);
    }

}
