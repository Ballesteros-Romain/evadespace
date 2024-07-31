<?php

namespace App\Controller;

use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/reservation', name: 'app_reservation')]
    public function index(): Response
    {
        return $this->render('reservation/index.html.twig');
    }

    #[Route('/api/reservations', name: 'api_reservations', methods: ['POST'])]
    public function createReservation(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['start_date'], $data['end_date'], $data['title'])) {
            return new JsonResponse(['error' => 'Invalid input'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $startDate = new \DateTime($data['start_date']);
        $endDate = new \DateTime($data['end_date']);
        $title = $data['title'];

        $reservation = new Reservation();
        $reservation->setStartDate($startDate);
        $reservation->setEndDate($endDate);
        $reservation->setTitle($title);

        $this->entityManager->persist($reservation);
        $this->entityManager->flush();

        return new JsonResponse(['success' => true, 'id' => $reservation->getId()]);
    }

    #[Route('/api/reservations', name: 'api_reservations_list', methods: ['GET'])]
    public function listReservations(): JsonResponse
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
}