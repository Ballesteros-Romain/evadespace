<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Review;
use App\Form\ReviewFormType;
use App\Repository\UserRepository;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReviewController extends AbstractController
{
    #[Route('/review', name: 'app_review')]
    public function index(Request $request, EntityManagerInterface $em, ReviewRepository $reviewRepository, UserRepository $userRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('home');
            $this->addFlash('danger', 'vous devez être connecté pour laisser un avis');
        } else {
                $user = $this->getUser();
                
                // Créez une nouvelle instance de Review
                $newReview = new Review();
                $newReview->setAuthor($user);
            // $firstname = $userRepository->find('firstname');
            // Créez le formulaire en utilisant la nouvelle instance de Review
            $reviewForm = $this->createForm(ReviewFormType::class, $newReview);
            
            $reviewForm->handleRequest($request);
            if ($reviewForm->isSubmitted() && $reviewForm->isValid()) {
                // Récupérez les données du formulaire
                $formData = $reviewForm->getData();
                $author = $user;
                $message = $formData->getMessage();
                $workspace = $formData->getWorkspace();
                $rating = $formData->getRating();
                
                // Créez une nouvelle instance de Review pour la sauvegarde en base de données
                $review = new Review();
                $review->setAuthor($user);
                $review->setMessage($message);
                $review->setWorkspace($workspace);
                $review->setRating($rating);
                
                try {
                    // Persistez et flush la nouvelle instance de Review
                    $em->persist($review);
                    $em->flush();
                    
                    return $this->redirectToRoute('home');
                    $this->addFlash('success', 'Nous avons pris en compte votre avis');
                } catch (\Exception $e) {
                    $this->addFlash('danger', 'Une erreur est survenue : ' . $e->getMessage());
                }
            }
        }
        // Récupérez les avis de la base de données
        $reviews = $reviewRepository->findAll();

            
            return $this->render('review/index.html.twig', [
            'controller_name' => 'ReviewController',
            'reviewForm' => $reviewForm->createView(),
            'reviews' => $reviews,
            'user' => $user // Passez les avis à la vue
        ]);
    }
}