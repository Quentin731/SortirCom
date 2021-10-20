<?php

namespace App\Controller\Admin;

use App\Entity\Trip;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TripCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Trip::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('tripName')->setLabel('nom'),
            TextEditorField::new('description'),
            DateTimeField::new('tripStartDate')->setLabel('Date de début'),
            DateField::new('endDate')->setLabel('date de fin'),
            IntegerField::new('duration')->setLabel('durée'),
            DateField::new('deadlineRegistrationDate')->setLabel('date de fin des inscriptions'),
            IntegerField::new('capacity')->setLabel('Capacité'),
            AssociationField::new('place')->autocomplete()->setLabel('Nom du lieu de la sortie'),
            AssociationField::new('organizer')->autocomplete()->setLabel('Organisateur'),

        ];
    }

}
