<?php

namespace App\Controller\Admin;

use App\Entity\Course;
use App\Entity\Instructor;
use App\Form\InstructorFormType;
use App\Repository\InstructorRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use Symfony\Component\Validator\Constraints\Choice;

class CourseCrudController extends AbstractCrudController
{


    private InstructorRepository $instructors;

    public function __construct(InstructorRepository $instructorRepository)
    {
        $this->instructors = $instructorRepository;
    }

    public static function getEntityFqcn(): string
    {
        return Course::class;
    }

    public function configureFields(string $pageName): iterable
    {

        yield 'Title';
        yield AssociationField::new('Department')
                ->setFormTypeOption('choice_label', 'name')
                ->setFormTypeOption('by_reference', 'id');
        yield AssociationField::new('CourseHead')
                ->setFormTypeOption('choice_label', 'user.username')
                ->setFormTypeOption('by_reference', 'id');
        yield AssociationField::new('Instructors')
            ->setFormTypeOption('choice_label', 'user.username');


    }
}
