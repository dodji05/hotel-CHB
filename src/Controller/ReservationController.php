<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ReservationType;
use App\Model\Reservation;
use App\Repository\PrixAAppliquerRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReservationController extends AbstractController
{
    #[Route('/reservation-hotel-chb', name: 'app_reservation')]
    public function reservation(Request $request, PrixAAppliquerRepository $prixAAppliquerRepository)
    {
        $reservation = new Reservation();

        $session = $request->getSession();

        $session->remove('chb_nb_jour');
        $session->remove('chb_reservation_periode');
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        $availableRooms = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $startDate = new  DateTime($form->get('dateArrive')->getData());
            $endDate = new  DateTime($form->get('dateDepart')->getData());
            $availableRooms = $prixAAppliquerRepository->findAvailableRooms($startDate, $endDate);
            $nbJour = date_diff($endDate, $startDate)->days;

            $chb_reservation_periode = [
                'dateDebut' => $startDate,
                'dateFin' => $endDate,
                'nbJours' => $nbJour,
            ];

            $session->set('chb_nb_jour', $nbJour);
            $session->set('chb_reservation_periode', $chb_reservation_periode);
//            dd($nbJour);

            return $this->render('front/chambres_disponible.html.twig', [
                //            'adherent' => $adherent,
                'availableRooms' => $availableRooms,
                'datedebut' => $startDate,
                'datefin' => $endDate,
            ]);

        }

//
        return $this->render('front/reservation_hotel.html.twig', [
            //            'adherent' => $adherent,
            'form' => $form,
        ]);
    }
}
