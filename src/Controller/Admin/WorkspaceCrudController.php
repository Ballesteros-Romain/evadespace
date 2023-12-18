<?php

namespace App\Controller\Admin;

use App\Entity\Workspace;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class WorkspaceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Workspace::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            ->hideOnDetail()
            ->hideOnForm()
            ->hideOnIndex(),
            TextField::new('name', 'Nom'),
            NumberField::new('capacity', 'Capacité'),
            MoneyField::new('price', 'Prix')->setCurrency('EUR'),
            BooleanField::new('availanility', 'Disponibilités'),
        ];
    
    }
    
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setPageTitle('index', 'Espaces de travail')
        ->setPaginatorPageSize(10);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
        ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setLabel('Créer un espace de travail');
            });
    }
}