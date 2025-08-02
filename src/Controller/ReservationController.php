<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Facture;
use App\Entity\LigneFacture;
use App\Entity\PrixAApplique;
use App\Form\ReservationType;
use App\Model\Reservation;
use App\Repository\PrixAAppliqueRepository;
use App\Service\ReservationService;
use App\Service\UniqueIdentifierGenerator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\CacheInterface;

class ReservationController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private ReservationService $reservationService
    ) {}
    /**
     * Handles hotel room reservation requests
     *
     * @Route("/reservation-hotel-chb", name="app_reservation")
     */
    #[Route('/reservation-hotel-chb', name: 'app_reservation')]
    public function reservation(
        Request $request,
        PrixAAppliqueRepository $prixAAppliqueRepository,
        LoggerInterface $logger,
        CacheInterface $cache
    ): Response {
        $session = $request->getSession();

        // Clear previous reservation data
        $this->clearReservationSession($session);

        // Initialize form
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        // Process dates from request or form
        try {
            $dateRange = $this->getDateRangeFromRequest($request, $form);
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('app_reservation');
        }

        // If we have valid dates, find available rooms
        if ($dateRange['startDate'] && $dateRange['endDate']) {
            // Validate date range
            $validationError = $this->validateDateRange($dateRange['startDate'], $dateRange['endDate']);
            if ($validationError) {
                $this->addFlash('error', $validationError);
                return $this->redirectToRoute('app_reservation');
            }

            try {
                // Find available rooms with caching
                $cacheKey = sprintf('available_rooms_%s_%s',
                    $dateRange['startDate']->format('Y-m-d'),
                    $dateRange['endDate']->format('Y-m-d')
                );

                $availableRooms = $cache->get($cacheKey, function(\Symfony\Contracts\Cache\ItemInterface $item) use ($prixAAppliqueRepository, $dateRange) {
                    $item->expiresAfter(3600); // Cache for 1 hour
                    return $prixAAppliqueRepository->findAvailableRooms(
                        $dateRange['startDate'],
                        $dateRange['endDate']
                    );
                });

                // Calculate number of nights
                $nbNuits = (int)$dateRange['endDate']->diff($dateRange['startDate'])->format('%a');

                // Save reservation period in session
                $this->saveReservationSession($session, $dateRange['startDate'], $dateRange['endDate'], $nbNuits);
//                $this->saveReservationPeriod($session, $dateRange['startDate'], $dateRange['endDate'], $nbNuits);

                return $this->render('front/chambres_disponible_2.html.twig', [
                    'availableRooms' => $availableRooms,
                    'datedebut' => $dateRange['startDate'],
                    'datefin' => $dateRange['endDate'],
                ]);

            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la recherche des chambres disponibles.'. $e->getMessage());
                $logger->error('Error finding available rooms: ' . $e->getMessage());
                return $this->redirectToRoute('app_reservation');
            }
        }

        // If no dates are selected, show the reservation form
        return $this->render('front/reservation_hotel.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/reservation', name: 'app_page_booking')]
    public function bookingPage(): Response
    {
        return $this->render('front/booking.html.twig');
    }
    #[Route('/api/rooms/available', name: 'api_available_rooms', methods: ['GET'])]
    public function getAvailableRooms(Request $request, PrixAAppliqueRepository $prixAAppliqueRepository): JsonResponse
    {
        $checkin = $request->query->get('checkin');
        $checkout = $request->query->get('checkout');

        if (!$checkin || !$checkout) {
            return $this->json(['error' => 'Dates de séjour requises'], 400);
        }

        try {
            $checkinDate = new \DateTime($checkin);
            $checkoutDate = new \DateTime($checkout);

            if ($checkinDate >= $checkoutDate) {
                return $this->json(['error' => 'Date de départ doit être après la date d\'arrivée'], 400);
            }

            $availableRooms = $this->reservationService->getAvailableRooms($checkinDate, $checkoutDate);


            // Serialize rooms to array
            $roomsArray = [];
            foreach ($availableRooms as $room) {
                $image = null;
                $produit = $room->getProduit();

                if ($produit) {
                    $imagesProduits = $produit->getImagesProduits();
                    if ($imagesProduits && !$imagesProduits->isEmpty()) {
                        $firstImage = $imagesProduits->first();
                        if ($firstImage) {
                            $image = $firstImage->getImage();
                        }
                    }
                }

//                dd($image);
                $roomsArray[] = [
                    'id' => $room->getId(),
                    'name' => $room->getProduit()->getLibelle(),
                    'description' => $room->getProduit()->getDescription(),
                    'price' => $room->getPrix(),
                    'image' =>  $image,
                    // Add other room properties as needed
                ];
            }
            $roomsJson = json_encode($roomsArray);
            return $this->json([
//                'rooms' => $this->serializer->serialize($availableRooms, 'json', ['groups' => ['room:read']]),
                'rooms' =>  $roomsJson ,
                'nights' => $checkinDate->diff($checkoutDate)->days
            ]);

        } catch (\Exception $e) {
            return $this->json(['error' => 'Erreur lors de la vérification des disponibilités'], 500);
        }
    }

    #[Route('/api/reservation', name: 'api_create_reservation', methods: ['POST'])]
    public function createReservation(Request $request,UniqueIdentifierGenerator $identifierGenerator): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            // Validation des données
            if (!$this->validateReservationData($data)) {
                return $this->json(['success' => false, 'message' => 'Données invalides'], 400);
            }

            $checkinDate = new \DateTime($data['checkin']);
            $checkoutDate = new \DateTime($data['checkout']);

            // Vérifier à nouveau la disponibilité des chambres
            foreach ($data['rooms'] as $roomData) {
                $room = $this->entityManager->getRepository(PrixAApplique::class)->find($roomData['id']);
                if (!$room || !$this->reservationService->isRoomAvailable($room, $checkinDate, $checkoutDate)) {
                    return $this->json([
                        'success' => false,
                        'message' => "La chambre {$room->getName()} n'est plus disponible pour ces dates"
                    ], 400);
                }
            }

            $client = new Client();
            $client->setNom($data['customer']['firstName']);
            $client->setPrenoms($data['customer']['lastName']);
            $client->setTelephone($data['customer']['phone']);
            $numclient = $identifierGenerator->generateUniqueIdentifier(Client::class, 'referenceSysteme', 'CL');
            $client->setReferenceManuel($numclient);
            $client->setReferenceSysteme($numclient);

            $this->entityManager->persist($client);

            // Créer la réservation
            $reservation =  new Facture();
            $numFacture = $identifierGenerator->generateUniqueIdentifier(Facture::class, 'referenceSysteme', 'E-FAC');

            $reservation->setReferenceManuel($numFacture);
            $reservation->setReferenceSysteme($numFacture);
            $reservation->setClient($client);
            $reservation->setDateFacture(new \DateTime());
            $reservation->setEstPaye(false);
            $reservation->setNormalisee(false);
            $reservation->setTotalht($data['total']);
//            $reservation->setDateArrive($data['customer']['arrivalTime'] ?? null);
            $reservation->setLocalisation('EN LIGNE');
            $reservation->setObservation($data['customer']['specialRequests'] ?? null);

            // Ajouter les chambres à la réservation
            foreach ($data['rooms'] as $roomData) {
                $room = $this->entityManager->getRepository(PrixAApplique::class)->find($roomData['id']);
                $reservationRoom = new LigneFacture();
                $reservationRoom->setFacture($reservation);
                $reservationRoom->setProduit($room);
                $reservationRoom->setLibelleProduit($room->getProduit()->getLibelle());
                $reservationRoom->setNbrejour($data['nights']);
                $reservationRoom->setPrixVente($roomData['price']);
                $reservationRoom->setDateEntree($checkinDate);
                $reservationRoom->setDateSortie($checkoutDate);
//                $reservationRoom->setRoomPrice($roomData['price'] * $data['nights']);

//                $reservation->addReservationRoom($reservationRoom);
                $this->entityManager->persist($reservationRoom);
            }

            // Valider l'entité
            $errors = $this->validator->validate($reservation);
            if (count($errors) > 0) {
                return $this->json(['success' => false, 'message' => 'Erreur de validation'], 400);
            }

            // Sauvegarder
            $this->entityManager->persist($reservation);
            $this->entityManager->flush();

            return $this->json([
                'success' => true,
                'reservationId' => $reservation->getId(),
                'message' => 'Réservation créée avec succès'
            ]);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la réservation: ' . $e->getMessage()
            ], 500);
        }
    }


    #[Route('/reservation/confirmation/{id}', name: 'reservation_confirmation')]
    public function confirmationPage(string $id): Response
    {
        $reservation = $this->entityManager->getRepository(Facture::class)->find($id);

        if (!$reservation) {
            throw $this->createNotFoundException('Réservation non trouvée');
        }

        return $this->render('front/confirmation.html.twig', [
            'reservation' => $reservation
        ]);
    }

    #[Route('/api/reservation/{id}', name: 'api_get_reservation', methods: ['GET'])]
    public function getReservation(string $id): JsonResponse
    {
        $reservation = $this->entityManager->getRepository(Facture::class)->find($id);

        if (!$reservation) {
            return $this->json(['error' => 'Réservation non trouvée'], 404);
        }

        return $this->json(
            $this->serializer->serialize($reservation, 'json', ['groups' => ['reservation:read']])
        );
    }

    private function validateReservationData(array $data): bool
    {
        $required = ['checkin', 'checkout', 'nights', 'rooms', 'total', 'customer'];

        foreach ($required as $field) {
            if (!isset($data[$field])) {
                return false;
            }
        }

        $customerRequired = ['firstName', 'lastName', 'email', 'phone'];
        foreach ($customerRequired as $field) {
            if (!isset($data['customer'][$field]) || empty($data['customer'][$field])) {
                return false;
            }
        }

        if (!filter_var($data['customer']['email'], FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }
    /**
     * Extracts date range from request or form
     */
    private function getDateRangeFromRequest(Request $request, \Symfony\Component\Form\FormInterface $form): array
    {
        $startDate = null;
        $endDate = null;

        // Check for direct date parameters (from the date range form)
        $dateArrive = $request->query->get('dateArrive');
        $dateDepart = $request->query->get('dateDepart');

        if ($dateArrive && $dateDepart) {
            try {
                $startDate = new \DateTime($dateArrive . ' 00:00:00');
                $endDate = new \DateTime($dateDepart . ' 23:59:59');
            } catch (\Exception $e) {
                throw new \Exception('Format de date invalide.');
            }
        }
        // Check for form submission
        elseif ($form->isSubmitted() && $form->isValid()) {
            $startDate = $form->get('dateArrive')->getData()->setTime(0, 0, 0);
            $endDate = $form->get('dateDepart')->getData()->setTime(23, 59, 59);
        }

        return [
            'startDate' => $startDate,
            'endDate' => $endDate
        ];
    }

    /**
     * Validates the date range
     */
    private function validateDateRange(\DateTime $startDate, \DateTime $endDate): ?string
    {
        $today = new \DateTime('today');

        if ($endDate <= $startDate) {
            return 'La date de départ doit être postérieure à la date d\'arrivée.';
        }

        if ($startDate < $today) {
            return 'La date d\'arrivée doit être aujourd\'hui ou une date ultérieure.';
        }

        // Maximum stay of 30 days
        $maxStay = new \DateInterval('P30D');
        $maxEndDate = (clone $startDate)->add($maxStay);
        if ($endDate > $maxEndDate) {
            return 'La durée maximale de séjour est de 30 nuits.';
        }

        return null;
    }

    /**
     * Saves reservation period in session
     */
    private function saveReservationSession($session, \DateTime $startDate, \DateTime $endDate, int $nbNuits): void
    {
        $chb_reservation_periode = [
            'dateDebut' => $startDate,
            'dateFin' => $endDate,
            'nbJours' => $nbNuits,
        ];

        $session->set('chb_nb_jour', $nbNuits);
        $session->set('chb_reservation_periode', $chb_reservation_periode);
    }

    /**
     * Clears reservation data from session
     */
    private function clearReservationSession($session): void
    {
        $session->remove('chb_nb_jour');
        $session->remove('chb_reservation_periode');
    }
}
