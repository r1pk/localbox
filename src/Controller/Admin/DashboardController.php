<?php

namespace App\Controller\Admin;

use App\Entity\File;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
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

    public function configureDashboard(): Dashboard
    {
        $dashboard = Dashboard::new();

        $dashboard->setTitle('LocalBox');
        $dashboard->setDefaultColorScheme(ColorScheme::DARK);
        $dashboard->renderContentMaximized();

        return $dashboard;
    }

    public function configureAssets(): Assets
    {
        $assets = parent::configureAssets();

        $assets->addAssetMapperEntry('admin');

        return $assets;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Home', 'fa fa-home');
        yield MenuItem::linkToCrud('Users', 'fa fa-user', User::class);
        yield MenuItem::linkToCrud('Files', 'fa fa-file', File::class);
    }

    public function configureCrud(): Crud
    {
        $crud = parent::configureCrud();

        $crud->overrideTemplates([
            'layout' => 'admin/layout.html.twig'
        ]);

        return $crud;
    }

    public function configureActions(): Actions
    {
        $actions = parent::configureActions();

        $actions->disable(Action::SAVE_AND_CONTINUE);
        $actions->disable(Action::SAVE_AND_ADD_ANOTHER);

        return $actions;
    }
}
