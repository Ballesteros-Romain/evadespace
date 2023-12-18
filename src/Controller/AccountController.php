<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\WorkspaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Stream;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(WorkspaceRepository $workspaceRepository): Response
    {
        $user = $this->getUser();
        if ($this->getUser()->hasRole('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin');
        }
        $work = $workspaceRepository->findAll();
        // $work = $workspaceRepository->find($name);
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
            'user' => $user,
            'works' => $work
        ]);
    }
}