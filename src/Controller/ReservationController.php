<?php

namespace App\Controller;

use App\Entity\Reservation;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Loader\Configurator\mailer;

#[IsGranted('ROLE_USER')]
class ReservationController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private MailerInterface $mailer;

    public function __construct(EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
    }

    

    private function sendReservationNotification(Reservation $reservation): void
    {    
        $startDate = $reservation->getStartDate()->setTimezone(new \DateTimeZone('Europe/Paris'));
        $endDate = $reservation->getEndDate()->setTimezone(new \DateTimeZone('Europe/Paris'));

        $email = (new Email())
            ->from('evadespace@contact.com')
            ->to('myriam.chadli@gmail.com') // Adresse de l'admin
            ->subject('Nouvelle Réservation')
            ->html(sprintf(
                '<p>Une nouvelle réservation a été effectuée :</p>
                <ul>
                    <li>Titre : %s</li>
                    <li>Date de début : %s</li>
                    <li>Date de fin : %s</li>
                </ul>',
                $reservation->getTitle(),
                $reservation->getStartDate()->format('d/m/Y H:i'),
                $reservation->getEndDate()->format('d/m/Y H:i')
            ));

        $this->mailer->send($email);
    }

    private function sendReservationUpdateNotification(Reservation $reservation): void
{
    $startDate = $reservation->getStartDate()->setTimezone(new \DateTimeZone('Europe/Paris'));
    $endDate = $reservation->getEndDate()->setTimezone(new \DateTimeZone('Europe/Paris'));

    $email = (new Email())
        ->from('no-reply@yourdomain.com')
        ->to('admin@yourdomain.com') // Adresse de l'admin
        ->subject('Réservation Modifiée')
        ->html(sprintf(
            '<p>Une réservation a été modifiée :</p>
            <ul>
                <li>Titre : %s</li>
                <li>Date de début : %s</li>
                <li>Date de fin : %s</li>
            </ul>',
            $reservation->getTitle(),
            $reservation->getStartDate()->format('d/m/Y H:i'),
            $reservation->getEndDate()->format('d/m/Y H:i')
        ));

    $this->mailer->send($email);
}

private function sendReservationDeletionNotification(Reservation $reservation): void
{
    $startDate = $reservation->getStartDate()->setTimezone(new \DateTimeZone('Europe/Paris'));
    $endDate = $reservation->getEndDate()->setTimezone(new \DateTimeZone('Europe/Paris'));

    $email = (new Email())
        ->from('no-reply@yourdomain.com')
        ->to('admin@yourdomain.com') // Adresse de l'administrateur
        ->subject('Réservation Supprimée')
        ->html(sprintf(
            '<p>Une réservation a été supprimée :</p>
            <ul>
                <li>Titre : %s</li>
                <li>Date de début : %s</li>
                <li>Date de fin : %s</li>
            </ul>',
            $reservation->getTitle(),
            $reservation->getStartDate()->format('d/m/Y H:i'),
            $reservation->getEndDate()->format('d/m/Y H:i')
        ));

    $this->mailer->send($email);
}



    #[Route('/reservation', name: 'app_reservation')]
    public function index(Request $request): Response
    {
        $this->getUser();
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

    // Envoyer l'email à l'administrateur
    $this->sendReservationNotification($reservation);

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

    // Envoyer l'email de notification pour modification
    $this->sendReservationUpdateNotification($reservation);

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

    // Envoyer l'email de notification avant de supprimer
    $this->sendReservationDeletionNotification($reservation);

    // Supprimer la réservation
    $this->entityManager->remove($reservation);
    $this->entityManager->flush();

    return new JsonResponse(['success' => true]);
}


    

    
}