<?php

namespace App\Controller\Admin;

use App\Entity\Assignment;
use App\Entity\Course;
use App\Entity\CourseWeek;
use App\Entity\Department;
use App\Entity\Faculty;
use App\Entity\Instructor;
use App\Entity\Lecture;
use App\Entity\Student;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegulatorController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/home.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Elearning')->setFaviconPath('favicon.svg')

            // the domain used by default is 'messages'
            ->setTranslationDomain('my-custom-domain')

            // there's no need to define the "text direction" explicitly because
            // its default value is inferred dynamically from the user locale
            ->setTextDirection('ltr')

            // by default, users can select between a "light" and "dark" mode for the
            // backend interface. Call this method if you prefer to disable the "dark"
            // mode for any reason (e.g. if your interface customizations are not ready for it)
            ->disableDarkMode()

            // by default, all backend URLs are generated as absolute URLs. If you
            // need to generate relative URLs instead, call this method
            ->generateRelativeUrls();
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),

            MenuItem::section('School'),
            MenuItem::linkToCrud('Faculties', 'fa fa-building', Faculty::class),
            MenuItem::linkToCrud('Departments', 'fa fa-building', Department::class),
            MenuItem::linkToCrud('Courses', 'fa fa-building', Course::class),

            MenuItem::section('Course'),
            MenuItem::linkToCrud('CourseWeek', 'fa fa-building', CourseWeek::class),
            MenuItem::linkToCrud('Lecture', 'fa fa-building', Lecture::class),
            MenuItem::linkToCrud('Assignment', 'fa fa-building', Assignment::class),

            MenuItem::section('Users'),
            MenuItem::linkToCrud('Users', 'fa fa-user', User::class),
            MenuItem::linkToCrud('Students', 'fa fa-user', Student::class),
            MenuItem::linkToCrud('Instructors', 'fa fa-user', Instructor::class)];
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
