<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Event;
use App\Entity\Tag;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
             ->setTitle('Mairie Site Gognie');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Articles', 'icon class', Article::class);
        yield MenuItem::linkToCrud('Evenements', 'icon class', Event::class);
        yield MenuItem::linkToCrud('Tags', 'icon class', Tag::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'icon class', User::class)
            ->setPermission('ROLE_ADMIN');
    }
}
