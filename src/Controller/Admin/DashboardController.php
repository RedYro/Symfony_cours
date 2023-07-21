<?php

namespace App\Controller\Admin;

use App\Entity\Users;
use App\Entity\Articles;
use App\Entity\Profiles;
use App\Entity\Addresses;
use App\Entity\Categories;
use App\Controller\Admin\UsersCrudController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Admin\ArticlesCrudController;
use App\Controller\Admin\ProfilesCrudController;
use App\Controller\Admin\AddressesCrudController;
use App\Controller\Admin\CategoriesCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(CategoriesCrudController::class)->generateUrl());
        return $this->redirect($adminUrlGenerator->setController(ArticlesCrudController::class)->generateUrl());
        return $this->redirect($adminUrlGenerator->setController(UsersCrudController::class)->generateUrl());
        return $this->redirect($adminUrlGenerator->setController(ProfilesCrudController::class)->generateUrl());
        return $this->redirect($adminUrlGenerator->setController(AddressesCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Blog Yro');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Back-office', 'fa fa-home');
        yield MenuItem::linkToCrud('Catégories', 'fas fa-list', Categories::class);
        yield MenuItem::linkToCrud('Articles', 'fas fa-list', Articles::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fa-solid fa-user', Users::class);
        yield MenuItem::linkToCrud('Profils', 'fa-regular fa-user', Profiles::class);
        yield MenuItem::linkToCrud('Addresses', 'fa-solid fa-address-book', Addresses::class);
    }
}
