<?php

namespace App\Controller\Admin;

use App\Entity\Blog;
use App\Entity\BlogAuthor;
use App\Enum\UserRole;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/", name="admin_dashboard")
     */
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('App');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::subMenu('Blog Content', 'fa-solid fa-blog')
            ->setSubItems([
                MenuItem::linkToCrud('Blog List', 'fa fa-list', Blog::class)
                    ->setPermission(UserRole::ROLE_ADMIN)
                    ->setController(BlogCrudController::class),
                MenuItem::linkToCrud('Pending Approvals', 'fas fa-hourglass', Blog::class)
                    ->setPermission(UserRole::ROLE_SUPER_ADMIN)
                    ->setController(BlogApprovalCrudController::class)
            ]);
        yield MenuItem::linkToCrud('Blog Author', 'fas fa-user', BlogAuthor::class);
    }

    public function configureCrud(): Crud
    {
        return Crud::new()
            ->showEntityActionsInlined()
            ->setDateTimeFormat('y-MM-dd');
    }
}
