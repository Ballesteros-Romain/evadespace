<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ReservationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reservation::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')-> hideOnIndex(),
            TextField::new('title', 'Nom'),
            DateTimeField::new('startDate')
            ->setFormat('d/M/Y H:mm')
            ->setTimezone('Europe/Paris'),  // Assurer le fuseau horaire correct
        DateTimeField::new('endDate')
            ->setFormat('d/M/Y H:mm')
            ->setTimezone('Europe/Paris'),
        ];
    }
    
}