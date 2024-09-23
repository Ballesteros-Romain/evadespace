<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OfficeSharingController extends AbstractController
{
    #[Route('/office/sharing', name: 'app_office_sharing')]
    public function index(): Response
    {
        

        return $this->render('office_sharing/index.html.twig', [
            'controller_name' => 'OfficeSharingController',
        ]);
    }

    #[Route('/office/meeting', name: 'app_office_meeting')]
    public function meeting()
    {

        return $this->render('office_sharing/meeting.html.twig', [

        ]);
    }

    #[Route('/office/event', name: 'app_office_event')]
    public function event()
    {

        return $this->render('office_sharing/event.html.twig', [

        ]);
    }

    #[Route('/office/private', name: 'app_office_private')]
    public function private()
    {

        return $this->render('office_sharing/private.html.twig', [

        ]);
    }

    #[Route('/office/work', name: 'app_office_work')]
    public function work()
    {

        return $this->render('office_sharing/work.html.twig', [

        ]);
    }

    #[Route('/office/domicile', name: 'app_office_domicile')]
    public function domicile()
    {

        return $this->render('office_sharing/domicile.html.twig', [

        ]);
    }

    #[Route('/RGPD', name: 'app_RGPD')]
    public function RGPD()
    {

        return $this->render('office_sharing/RGPD.html.twig', [

        ]);
    }
}