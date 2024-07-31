<?php

namespace App\Controller;

use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/api/events', name: 'api_events', methods: ['GET'])]
    public function listEvents(): JsonResponse
    {
        $reservations = $this->entityManager->getRepository(Reservation::class)->findAll();
        $events = [];

        foreach ($reservations as $reservation) {
            $events[] = [
                'id' => $reservation->getId(),
                'title' => $reservation->getTitle(),
                'start' => $reservation->getStartDate()->format('Y-m-d\TH:i:s'),
                'end' => $reservation->getEndDate()->format('Y-m-d\TH:i:s'),
            ];
        }

        return new JsonResponse($events);
    }

    #[Route('/api/events/{id}', name: 'api_event_get', methods: ['GET'])]
    public function getEvent(int $id): JsonResponse
    {
        $reservation = $this->entityManager->getRepository(Reservation::class)->find($id);
        if (!$reservation) {
            return new JsonResponse(['error' => 'Event not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $event = [
            'id' => $reservation->getId(),
            'title' => $reservation->getTitle(),
            'start' => $reservation->getStartDate()->format('Y-m-d\TH:i:s'),
            'end' => $reservation->getEndDate()->format('Y-m-d\TH:i:s'),
        ];

        return new JsonResponse($event);
    }
}