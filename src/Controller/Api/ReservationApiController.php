<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Repository\PrixAAppliqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/reservation')]
class ReservationApiController extends AbstractController
{
    public function __construct(
        private PrixAAppliqueRepository $prixAAppliqueRepository,
        private SerializerInterface $serializer
    ) {
    }

    /**
     * Search for available rooms via AJAX
     */
    #[Route('/search-rooms', name: 'api_reservation_search_rooms', methods: ['GET'])]
    public function searchAvailableRooms(Request $request): JsonResponse
    {
        try {
            // Get and validate date parameters
            $dateArrive = $request->query->get('dateArrive');
            $dateDepart = $request->query->get('dateDepart');

            if (!$dateArrive || !$dateDepart) {
                return $this->json([
                    'success' => false,
                    'message' => 'Les dates de début et de fin sont requises.'
                ], 400);
            }

            // Parse dates
            $startDate = new \DateTime($dateArrive . ' 00:00:00');
            $endDate = new \DateTime($dateDepart . ' 23:59:59');

            // Validate date range
            if ($endDate <= $startDate) {
                return $this->json([
                    'success' => false,
                    'message' => 'La date de départ doit être postérieure à la date d\'arrivée.'
                ], 400);
            }

            $today = new \DateTime('today');
            if ($startDate < $today) {
                return $this->json([
                    'success' => false,
                    'message' => 'La date d\'arrivée doit être aujourd\'hui ou une date ultérieure.'
                ], 400);
            }

            // Get available rooms
            $availableRooms = $this->prixAAppliqueRepository->findAvailableRooms($startDate, $endDate);

            // Serialize rooms to array
            $roomsArray = [];
            foreach ($availableRooms as $room) {
                $roomsArray[] = [
                    'id' => $room->getId(),
                    'name' => $room->getProduit()->getLibelle(),
                    'description' => $room->getProduit()->getDescription(),
                    'price' => $room->getPrix(),
                    'image' => null,
                    // Add other room properties as needed
                ];
            }

            return $this->json([
                'success' => true,
                'rooms' => $roomsArray,
                'dateDebut' => $startDate->format('Y-m-d'),
                'dateFin' => $endDate->format('Y-m-d'),
                'nbNuits' => (int)$endDate->diff($startDate)->format('%a')
            ]);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la recherche des chambres.'
            ], 500);
        }
    }
}
