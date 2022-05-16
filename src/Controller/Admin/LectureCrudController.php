<?php

namespace App\Controller\Admin;

use App\Entity\Lecture;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class LectureCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Lecture::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield 'title';
        yield 'description';
        yield 'video';
        yield 'file';
        yield 'title';
        yield AssociationField::new('CourseWeek')
            ->setFormTypeOption('choice_label', 'cardinality')
            ->setFormTypeOption('by_reference', 'id');
        yield AssociationField::new('Instructors')
            ->setFormTypeOption('choice_label', 'user.username');
    }
}
