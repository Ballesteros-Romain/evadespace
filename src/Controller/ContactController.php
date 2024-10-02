<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;
use Doctrine\ORM\EntityManager;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(EntityManagerInterface $em, ContactRepository $contactRepository, Request $request): Response
    {
        $contactForm = $this->createForm(ContactFormType::class);

        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $formData = $contactForm->getData();
            $email = $formData->getEmail();
            $message = $formData->getMessage();

            $contact = new Contact();
            $contact->setEmail($email);
            $contact->setMessage($message);

            try{
                $em->persist($contact);
                $em->flush();

                $this->addFlash('success', 'Nous avons pris en compte votre message');
                return $this->redirectToRoute('home');
            }catch (\Exception $e) {
                $this->addFlash('danger', 'Une erreur est survenue : ' . $e->getMessage());
            }
        }

            $contact = $contactRepository->findAll();
            $context = compact('contact');
            $context['avis'] = $contact;


        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'contactForm' => $contactForm->createView(),
            'contact' => $contact,
        ]);
    }
}