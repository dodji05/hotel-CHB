<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(Request $request)
    {
        return $this->render('front/index.html.twig', [
            //            'adherent' => $adherent,
            //            'form' => $form,
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

    #[Route('/hebergement', name: 'app_hebergement')]
    public function hebergement(Request $request)
    {
        return $this->render('front/hebergement.html.twig', [
            //            'adherent' => $adherent,
            //            'form' => $form,
        ]);
    }
    #[Route('/hebergement/chambres-d-hotel-et-repos', name: 'app_hebergement_hotel')]
    public function hotel(Request $request)
    {
        return $this->render('front/hebergement_hotel.html.twig', [
            //            'adherent' => $adherent,
            //            'form' => $form,
        ]);
    }
    #[Route('/hebergement/details', name: 'app_hebergement_details')]
    public function chambres(Request $request)
    {
        return $this->render('front/chambres-details.html.twig', [
            //            'adherent' => $adherent,
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
        $view = 'front/'.$request->get('services').'.html.twig';
       // dd($view);
        return $this->render($view, [
            //            'adherent' => $adherent,
            //            'form' => $form,
        ]);
    }
}
