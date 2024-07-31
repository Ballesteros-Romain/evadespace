<?php
namespace App\Controller;

use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    #[Route('/api/events', name: 'api_events')]
    public function getEvents(ReservationRepository $reservationRepository): JsonResponse
    {
        $reservations = $reservationRepository->findAll();

        $events = [];

        foreach ($reservations as $reservation) {
            $events[] = [
                'title' => $reservation->getWorkspace()->getName(), // Assuming Workspace has a `getName` method
                'start' => $reservation->getStartDate()->format('Y-m-d H:i:s'),
                'end' => $reservation->getEndDate()->format('Y-m-d H:i:s'),
                'backgroundColor' => '#5d6371', // Optional: Customize colors if needed
            ];
        }

        return new JsonResponse($events);
    }
}