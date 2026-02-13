<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Option\ColorScheme;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->render('@EasyAdmin/page/content.html.twig');
    }

    public function configureCrud(): Crud
    {
        $crud = parent::configureCrud();

        $crud->overrideTemplates([
            'layout' => 'admin/layout.html.twig'
        ]);

        return $crud;
    }

    public function configureDashboard(): Dashboard
    {
        $dashboard = Dashboard::new();

        $dashboard->setTitle('LocalBox');
        $dashboard->renderContentMaximized();
        $dashboard->setDefaultColorScheme(ColorScheme::DARK);

        return $dashboard;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Home', 'fa fa-home');
    }
}
