<?php

namespace App\Controller;

use App\Entity\Famille;
use App\Entity\PrixAAppliquer;

use App\Repository\FamilleRepository;
use App\Repository\PresentationRepository;
use App\Repository\PrixAAppliquerRepository;

use App\Repository\ServicesRepository;
use App\Repository\SliderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(Request $request, SliderRepository $sliderRepository, ServicesRepository $servicesRepository,
                          PrixAAppliquerRepository $prixAAppliquerRepository, PresentationRepository $presentationRepository)
    {
        $session = $request->getSession();
        $session->remove('chb_nb_jour');
        $session->remove('chb_panier');
        $session->remove('chb_panier_facture');
        $session->remove('chb_panier_order');
        $session->remove('chb_reservation_periode');
        $session->remove('chb_panier_type');
        $sliders = $sliderRepository->findAll();
        $hebergement = $servicesRepository->findOneBy(['codeService' => 'S0000011']);
        $restaurant = $servicesRepository->findOneBy(['codeService' => 'S00000001']);
        $roof = $servicesRepository->findOneBy(['codeService' => 'S0000002']);
        $piza = $servicesRepository->findOneBy(['codeService' => 'S0000003']);
        $location = $servicesRepository->findOneBy(['codeService' => 'S0000008']);
//        dd($hebergement,$restaurant,$piza);
        $chambres = $prixAAppliquerRepository->findProduitFamilleByService('S0000011', 4);
        $presentation = $presentationRepository->find(1);
        //  dd($chambres);
        return $this->render('front/index.html.twig', [
            //            'adherent' => $adherent,
            //            'form' => $form,
            'sliders' => $sliders,
            'hebergement' => $hebergement,
            'restaurant' => $restaurant,
            'roof' => $roof,
            'piza' => $piza,
            'location' => $location,
            'chambres' => $chambres,
            'presentation' => $presentation,
        ]);
    }

    #[Route('/restaurant', name: 'app_restaurant')]
    public function restaurant(Request $request)
    {
        return $this->render('front/restaurant.html.twig', [
            //            'adherent' => $adherent,
            //            'form' => $form,
        ]);
    }


    #[Route('/restaurant/menu', name: 'app_restaurant_menu')]
    public function restaurantMenu(Request $request, PrixAAppliquerRepository $prixAAppliquerRepository)
    {
        $menu = $prixAAppliquerRepository->findProduitByService('S00000001');
        // dd($menu);
        return $this->render('front/restaurant_menu.html.twig', [
            //            'adherent' => $adherent,
            //            'form' => $form,
            'menus' => $menu
        ]);
    }

    #[Route('/restaurant/le-menu', name: 'app_restaurant_menu1')]
    public function notreMenu(Request $request, FamilleRepository $familleRepository, PrixAAppliquerRepository $prixAAppliquerRepository)
    {
        $menu = $prixAAppliquerRepository->findProduitFamilleByService('S00000001',true);
        $famille = $familleRepository->findBy(['numero' => '']);
//       dd($famille );
        return $this->render('front/menu-restaurant.html.twig', [
            //            'adherent' => $adherent,
            //            'form' => $form,
            'menus' => $menu,
            'famille' => $famille
        ]);
    }

    #[Route('/restaurant/menu/{codeprixApplique}', name: 'app_restaurant_menu_details')]
    public function plats(Request $request, PrixAAppliquer $prixAAppliquer, PrixAAppliquerRepository $prixAAppliquerRepository)
    {
//        $menu = $prixAAppliquerRepository->findProduitByService('S00000001');
        // dd($menu);
        return $this->render('front/menu_details.html.twig', [
            //            'adherent' => $adherent,
            //            'form' => $form,
            'produit' => $prixAAppliquer
        ]);
    }

    #[Route('/hebergement', name: 'app_hebergement')]
    public function hebergement(FamilleRepository $familleRepository)
    {
        $familles = $familleRepository->findBy(['numero' => 3]);

        return $this->render('front/hebergement.html.twig', [
            //            'adherent' => $adherent,
                       'familles' => $familles,
        ]);
    }

    #[Route('/hebergement/{slug}', name: 'app_hebergement_hotel')]
    public function hotel(Request $request,Famille $famille,PrixAAppliquerRepository $prixAAppliquerRepository)
    {
        $codeFamille = $famille->getCodeFamille();
        $chambres = $prixAAppliquerRepository->findProduitFamilleByServiceHebergement('S0000011',$codeFamille);
//        dd($chambres);
        return $this->render('front/hebergement_hotel.html.twig', [
            //            'adherent' => $adherent,
            'famille'=>$famille,
            'chambres' => $chambres,
        ]);
    }

    #[Route('/hebergement/details/{codeprixApplique}', name: 'app_hebergement_details')]
    public function chambres(Request $request,PrixAAppliquer $prixAAppliquer)
    {
        return $this->render('front/chambres-details.html.twig', [
                        'chambre' => $prixAAppliquer,
            //            'form' => $form,
        ]);
    }

    #[Route('/hebergement/details-demo', name: 'app_hebergement_details1')]
    public function chambresdetails(Request $request,PrixAAppliquer $prixAAppliquer)
    {
        return $this->render('front/detailsChambresDemo.html.twig', [
           // 'chambre' => $prixAAppliquer,
            //            'form' => $form,
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request)
    {
        return $this->render('front/contact.html.twig', [
            //            'adherent' => $adherent,
            //            'form' => $form,
        ]);
    }

    #[Route('/services/{services}', name: 'app_autres_services')]
    public function autresServices(Request $request)
    {
        $view = 'front/' . $request->get('services') . '.html.twig';
        // dd($view);
        return $this->render($view, [
            //            'adherent' => $adherent,
            //            'form' => $form,
        ]);
    }


}
