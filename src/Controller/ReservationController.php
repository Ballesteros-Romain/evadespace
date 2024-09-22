<?php

namespace App\Controller;

use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
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
    public function index(Request $request): Response
    {
        $workspaceId = $request->query->get('workspace');

        return $this->render('reservation/index.html.twig', [
            'workspaceId' => $workspaceId,
        ]);
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

    #[Route('/api/reservations/{id}', name: 'api_reservation_update', methods: ['PUT'])]
    public function updateReservation(Request $request, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $reservation = $this->entityManager->getRepository(Reservation::class)->find($id);
        if (!$reservation) {
            return new JsonResponse(['error' => 'Reservation not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        if (isset($data['start_date'])) {
            $reservation->setStartDate(new \DateTime($data['start_date']));
        }
        if (isset($data['end_date'])) {
            $reservation->setEndDate(new \DateTime($data['end_date']));
        }
        if (isset($data['title'])) {
            $reservation->setTitle($data['title']);
        }

        $this->entityManager->flush();

        return new JsonResponse(['success' => true]);
    }

     #[Route('/api/reservations', name: 'api_reservation_delete', methods: ['DELETE'])]
    public function deleteReservation(Request $request, ReservationRepository $reservationRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['title'])) {
            return new JsonResponse(['error' => 'Invalid input'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $reservation = $reservationRepository->findOneBy(['title' => $data['title']]);

        if (!$reservation) {
            return new JsonResponse(['error' => 'Reservation not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($reservation);
        $this->entityManager->flush();

        return new JsonResponse(['success' => true]);
    }
}