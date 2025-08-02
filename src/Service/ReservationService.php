<?php

namespace App\Service;

use App\Entity\PrixAApplique;
use App\Repository\PrixAAppliqueRepository;
use Doctrine\ORM\EntityManagerInterface;

class ReservationService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private PrixAAppliqueRepository $roomRepository
    ) {}

    public function getAvailableRooms(\DateTime $checkinDate, \DateTime $checkoutDate): array
    {
        return $this->roomRepository->findAvailableRooms($checkinDate, $checkoutDate);
    }

    public function isRoomAvailable(PrixAApplique $room, \DateTime $checkinDate, \DateTime $checkoutDate): bool
    {
        $qb = $this->entityManager->createQueryBuilder();

        $conflicts = $qb->select('COUNT(rr.id)')
            ->from('App\Entity\PrixAApplique', 'rr')
            ->join('rr.ligneFactures', 'r')
            ->where('rr.produit = :room')
//            ->andWhere('r.status != :cancelled')
            ->andWhere(
                $qb->expr()->not(
                    $qb->expr()->orX(
                        $qb->expr()->lte('r.dateEntree', ':checkin'),
                        $qb->expr()->gte('r.dateSortie', ':checkout')
                    )
                )
            )
            ->setParameter('room', $room->getId()->toBinary())
            ->setParameter('checkin', $checkinDate->format('Y-m-d'))
            ->setParameter('checkout', $checkoutDate->format('Y-m-d'))
            ->getQuery()
            ->getSingleScalarResult();

        return $conflicts == 0;
    }

    public function calculateTotalPrice(array $rooms, int $nights): float
    {
        $total = 0;
        foreach ($rooms as $roomData) {
            $room = $this->entityManager->getRepository(PrixAApplique::class)->find($roomData['id']);
            if ($room) {
                $total += (float)$room->getPrice() * $nights;
            }
        }
        return $total;
    }


}
