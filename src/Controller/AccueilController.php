<?php

namespace App\Controller;

use App\Entity\Famille;
use App\Entity\PrixAApplique;
use App\Entity\PrixAAppliquer;

use App\Repository\FacilitiesRepository;
use App\Repository\FamilleRepository;
use App\Repository\GaleriePhotosRepository;
use App\Repository\PresentationRepository;
use App\Repository\PrixAAppliqueRepository;
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
                          PrixAAppliquerRepository $prixAAppliquerRepository, PresentationRepository $presentationRepository, FacilitiesRepository $facilitiesRepository
    ,PrixAAppliqueRepository $prixAAppliqueRepository)
    {
        $session = $request->getSession();
        $session->remove('chb_nb_jour');
        $session->remove('chb_panier');
        $session->remove('chb_panier_facture');
        $session->remove('chb_panier_order');
        $session->remove('chb_reservation_periode');
        $session->remove('chb_panier_type');
        $sliders = $sliderRepository->findAll();
        $hebergement = $servicesRepository->findOneBy(['referenceManuel' => 'S0000011']);
        $restaurant = $servicesRepository->findOneBy(['referenceManuel' => 'S00000001']);
        $roof = $servicesRepository->findOneBy(['referenceManuel' => 'S0000002']);
        $piza = $servicesRepository->findOneBy(['referenceManuel' => 'S0000004']);
        $location = $servicesRepository->findOneBy(['referenceManuel' => 'S0000008']);
//        $platsDuJours = $prixAAppliquerRepository->findMets(false,true,6);
        $platsDuJours = null;
        $servicesFeat =  $servicesRepository->findBy(['featured'=>true]);
//        dd($hebergement,$restaurant,$piza);
        $chambres = $prixAAppliqueRepository->findProduitFamilleByServiceFeat('S0000011', false,5);
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
            'services'=>$servicesFeat,
            'equipements'=> $facilitiesRepository->findBy(['IsActive'=>true]),
            'platsDujours' =>$platsDuJours
        ]);
    }

    #[Route('/complexe-hotelier-la-bonte', name: 'app_a_propos')]
    public function aPropos(PresentationRepository $presentationRepository)
    {
        $presentation = $presentationRepository->find(1);
        return $this->render('front/abousUS.html.twig',[
            "presentation"=> $presentation
        ])  ;
    }

    #[Route('/galerie', name: 'app_photo')]
    public function galeriePhoto(GaleriePhotosRepository $galeriePhotosRepository)
    {
//        $familles = $familleRepository->findBy(['numero' => 3]);
        $galerie = $galeriePhotosRepository->findAll();

        return $this->render('front/galerie.html.twig', [
            //            'adherent' => $adherent,
            'galeries' =>  $galerie ,
        ]);
    }
    #[Route('/restaurant', name: 'app_restaurant')]
    public function restaurant(Request $request, PrixAAppliqueRepository $prixAAppliqueRepository)
    {
        $menu = $prixAAppliqueRepository->findMets();;
        return $this->render('front/restaurant.html.twig', [
            'menus' => $menu
            //            'adherent' => $adherent,
            //            'form' => $form,
        ]);
    }


    #[Route('/restaurant/menu', name: 'app_restaurant_menu')]
    public function restaurantMenu(Request $request, PrixAAppliquerRepository $prixAAppliquerRepository)
    {
//        $menu = $prixAAppliquerRepository->findProduitByService('S00000001');
        $menu = $prixAAppliquerRepository->findMets();;
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

    #[Route('/restaurant/menu/{id}', name: 'app_restaurant_menu_details')]
    public function plats(Request $request, PrixAApplique $prixAApplique, PrixAAppliqueRepository $prixAAppliqueRepository)
    {
//        $menu = $prixAAppliquerRepository->findProduitByService('S00000001');
        // dd($menu);
        return $this->render('front/menu_details.html.twig', [
            //            'adherent' => $adherent,
            //            'form' => $form,
            'produit' => $prixAApplique
        ]);
    }

    #[Route('/hebergement', name: 'app_hebergement')]
    public function hebergement(FamilleRepository $familleRepository)
    {
//        $familles = $familleRepository->findBy(['numero' => 3]);
        $familles = $familleRepository->findTypeHebergment();

        return $this->render('front/hebergement.html.twig', [
            //            'adherent' => $adherent,
                       'familles' => $familles,
        ]);
    }

    #[Route('/hebergement/{slug}', name: 'app_hebergement_hotel')]
    public function hotel(Request $request, FamilleRepository $familleRepository,ServicesRepository $servicesRepository,PrixAAppliqueRepository
    $prixAAppliqueRepository)
    {
//       dd($request->get('slug'));
        $slug = $request->get('slug');
        $famille = $familleRepository->findFamilleBySlugOrCodeFamille($slug);
        $codeFamille = $famille->getreferenceManuel();
        $service  = $servicesRepository->findOneBy(['libelle' => 'HEBERGEMENT']);
        $chambres =$prixAAppliqueRepository->produitParService( $service);
//        $chambres = $prixAAppliqueRepository->findProduitFamilleByServiceHebergement('S0000011',$codeFamille);
//        dd($chambres);
        return $this->render('front/hebergement_hotel.html.twig', [
            //            'adherent' => $adherent,
            'famille'=>$famille,
            'chambres' => $chambres,
        ]);
    }

    #[Route('/hebergement/details/{id}', name: 'app_hebergement_details')]
    public function chambres(Request $request,PrixAApplique $prixAAppliquer)
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

    #[Route('/services/{slug}', name: 'app_autres_services')]
    public function autresServices(Request $request, ServicesRepository $servicesRepository)
    {
        $slug = $request->get('slug');
        $service = $servicesRepository->findServiceBySlugOrCodeFamille($slug);
        // dd($view);
        return $this->render('front/services.html.twig', [
            //            'adherent' => $adherent,
            //            'form' => $form,
            'service'=>$service

        ]);
    }


}
