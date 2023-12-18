<?php

namespace App\Controller;

use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PaymentController extends AbstractController
{
    #[Route('/order/create-session-stripe', name: 'app_payment')]
//     // public function __construct(readonly private string $clientSecret)
//     // {
//     //     Stripe::setApiKey($this->clientSecret); 
//     //     Stripe::setApiVersion('2023-10-16');
//     // }

//     public function stripeChekout(): RedirectResponse
//     {
//         Stripe::setApiKey(sk_test_51ONPlRHBZr5XCfQnyvr7WqsePpV6X65XjiWe60wrz6H6GelZ2mwEnjF17jMX0F7ef49ybzfjjr9rCLpkCX3GcdI5001BJdYsgq);
// // header('Content-Type: application/json');

// // $YOUR_DOMAIN = 'http://localhost:4242';

// $checkout_session = \Stripe\Checkout\Session::create([
//   'line_items' => [[
//     # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
//     'price' => '{{PRICE_ID}}',
//     'quantity' => 1,
//   ]],
//   'mode' => 'payment',
//   'success_url' => $YOUR_DOMAIN . '/success.html',
//   'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
// ]);

// header("HTTP/1.1 303 See Other");
// header("Location: " . $checkout_session->url);
//     }


    // public function startPayment(){

    // }
    #[Route('/tarif', name: 'app_tarif')]
    public function index(): Response
    {
        return $this->render('payment/index.html.twig', [
          'controller_name' => 'PaymentController',
        ]);
      }
}