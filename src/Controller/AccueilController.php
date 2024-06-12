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
        return $this->render('front/index.html.twig', [
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

    #[Route('/hebergement/details', name: 'app_hebergement_details')]
    public function chambres(Request $request)
    {
        return $this->render('front/index.html.twig', [
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
}
