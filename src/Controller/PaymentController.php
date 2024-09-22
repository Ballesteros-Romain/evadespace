<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Workspace;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class PaymentController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/payment', name: 'app_payment', methods: ['POST'])]
    public function stripeChekout(Request $request): RedirectResponse
    {
        // TODO passer en env pour la sécurité
        Stripe::setApiKey("sk_test_51ONPlRHBZr5XCfQnyvr7WqsePpV6X65XjiWe60wrz6H6GelZ2mwEnjF17jMX0F7ef49ybzfjjr9rCLpkCX3GcdI5001BJdYsgq");
        Stripe::setApiVersion('2024-06-20');
        
        $workspace = $this->entityManager->getRepository(Workspace::class)->find((int)$request->request->get('workspaceId'));
        // TODO Il faut stocker dans la session la date et heure choisie par le client/e (intercepté au clic sur Payer via le JavaScript)

        // TODO mettre l'URL dans le env plus tard
        $url = 'https://localhost:8000';

        $checkout_session = \Stripe\Checkout\Session::create([
            'line_items' => [[
                // TODO ajouter une colonne stripe price ID sur l'entité Workspace pour stocker la valeur pour chaque plan
                'price' => 'price_1Q1sYaHBZr5XCfQneqshWwR1',
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('payment_success'),
            'cancel_url' => $this->generateUrl('payment_error'),
        ]);
            
        header("HTTP/1.1 303 See Other");
        header("Location: " . $checkout_session->url);
    }

    #[Route('/payment/success', name: 'payment_success')]
    public function handleSuccessPayment(Request $request)
    {
        // TODO Ici, tu dois créer la réservation puisque le client a payé, donc tu valides
        // Récupérer les données que tu as stocké en session afin de créer la réservation avec la date et heure choisie

        return $this->render('payment/success.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }

    #[Route('/payment/error', name: 'payment_error')]
    public function handleErrorPayment(Request $request)
    {
        return $this->render('payment/error.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }

    #[Route('/tarif', name: 'app_tarif')]
    public function index(): Response
    {
        return $this->render('payment/index.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }
}