<?php

namespace App\Controller\Admin;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\User;
use App\Entity\Ville;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('SortirCom Administration');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Sorties', 'fas fa-list', Sortie::class);
        yield MenuItem::linkToCrud('Lieux', 'fas fa-list', Lieu::class);
        yield MenuItem::linkToCrud('Villes', 'fas fa-list', Ville::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-list', User::class);
    }
}
