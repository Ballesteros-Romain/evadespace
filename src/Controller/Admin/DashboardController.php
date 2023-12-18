<?php

namespace App\Controller\Admin;

use App\Entity\Payment;
use App\Entity\Text;
use App\Entity\User;
use App\Entity\Title;
use App\Entity\Review;
use App\Entity\Workspace;
use App\Entity\Reservation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());      
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Evadespace')
            ->setLocales(['fr']);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa-solid fa-user', User::class);
        yield MenuItem::linkToCrud('Réservation', 'fa-solid fa-calendar', Reservation::class);
        yield MenuItem::linkToCrud('Avis', 'fa-solid fa-comment', Review::class);
        // yield MenuItem::linkToCrud('Titres', 'fa-solid fa-folder', Title::class);
        // yield MenuItem::linkToCrud('Textes', 'fa-solid fa-folder-open', Text::class);
        yield MenuItem::linkToCrud('Workspace', 'fa-solid fa-box', Workspace::class);
        yield MenuItem::linkToCrud('Paiement', 'fa-solid fa-credit-card', Payment::class);
    }
}