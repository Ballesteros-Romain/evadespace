<?php

namespace App\Controller;

use App\Repository\ReviewRepository;
use App\Repository\WorkspaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ReviewRepository $reviewRepository, WorkspaceRepository $workspaceRepository): Response
    {
        $user = $this->getUser();
        $reviews = $reviewRepository->findAll();
        $workspace = $workspaceRepository->findAll();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'reviews' => $reviews,
            'user' => $user,
            'workspaces' => $workspace
        ]);
    }
}